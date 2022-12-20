<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverterBlogger;
use App\Helpers\Import\TimestampToDateConverter;
use App\Helpers\ImportImage;
use App\Models\Article;
use App\Models\Author;
use App\Models\Tag;
use App\XMLReaders\ArticleBloggerXMLReader;
use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportArticlesBlogger extends Command
{
    private ImportImage $save_image;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:articles-blogger
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports articles from Blogger XML file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImportImage $save_image)
    {
        parent::__construct();
        $this->save_image = $save_image;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ArticleBloggerXMLReader $articleXMLReader, HtmlToEditorJsConverterBlogger $converter, TimestampToDateConverter $timeconverter)
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $count = substr_count(file_get_contents($path), '#post');
        $progress = $this->output->createProgressBar($count);
        $progress->start();

        $articleXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress, $timeconverter, &$i) {
            if (! isset($data['is_article']) || empty($data['content'])) {
                return;
            }
            $article = Article::where('legacy_id', $data['id'])->first() ?? new Article();

            if ($article->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }
                $article->delete();
                $article = new Article();
            }

            try {
                if (str_contains($data['author'], 'wishes')) {
                    $author = Author::where('slug', 'like', '%wishes%')->orWhere('name', 'like', '%wishes%')->first();
                } else {
                    $author = Author::where('slug', '=', Str::slug($data['author']))->first();
                }
                if (! $author) {
                    $author = new Author();
                    $author->slug = Str::slug($data['author']);
                    $author->name = $data['author'];
                    $author->save();
                }

                $article->legacy_id = $data['id'];
                $article->title = $data['title'];
                if (isset($data['slug'])) {
                    $article->slug = $data['slug'];
                } else {
                    $article->slug = Str::slug($data['title']);
                }

                $article->body = $converter->convert($data['content'], 'article');

                $article->published_at = Carbon::createFromTimeString($data['published'])->toDateTimeString();
                $article->created_at = Carbon::createFromTimeString($data['updated'])->toDateTimeString();
                $article->updated_at = Carbon::createFromTimeString($data['updated'])->toDateTimeString();


                $article->save();
                $article->authors()->attach($author->id);

                if (isset($data['tags'])) {
                    foreach ($data['tags'] as $tagName) {
                        $tag = Tag::whereName($tagName)->first();
                        if (! $tag) {
                            $tag = new Tag();
                            $tag->name = $tagName;
                            $tag->slug = Str::slug($tagName);
                            $tag->save();
                        }
                        $article->tags()->attach($tag->id);
                    }
                }
            } catch (\Throwable $e) {
                $this->info("\nException: " . $e->getMessage());
                $this->info($data['id']);
                $this->info($data['content']);
            } finally {
                $progress->advance();
            }
        });

        $progress->finish();
        $this->info("\nImporting is finished. Number of skipped records: " . $skipped);

        return 0;
    }
}

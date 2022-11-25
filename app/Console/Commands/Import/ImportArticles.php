<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverterMagazine;
use App\Helpers\Import\TimestampToDateConverter;
use App\Helpers\ImportImage;
use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;

use App\Models\User;
use App\XMLReaders\ArticleXMLReader;
use Illuminate\Console\Command;

class ImportArticles extends Command
{
    private ImportImage $save_image;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:articles
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports articles from XML file';

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
    public function handle(ArticleXMLReader $articleXMLReader, HtmlToEditorJsConverterMagazine $converter, TimestampToDateConverter $timeconverter)
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($articleXMLReader->count($path));
        $progress->start();

        $articleXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress, $timeconverter) {
            $article = Article::where('legacy_id', '=', $data['id'])->first() ?? new Article();

            if ($article->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }
                $article->delete();
                $article = new Article();
            }

            $body = (implode('', $data['embeds']) . $data['body']) ?? null;

            try {
                $article->legacy_id = $data['id'] ?? null;

                $imageId = null;
                if ($data['_thumbnail_id'] ?? false) {
                    $imageId = Media::query()
                        ->where('legacy_id', '=', $data['_thumbnail_id'])
                        ->first()?->id;
                }

                $article->image_id = $imageId;

                $article->title = $data['_aioseop_title'] ?? $data['title'];
                $article->title .= ! $data['active'] ? ' [DRAFT]' : '';

                $article->slug = $data['slug'];
                $article->lead = $data['_aioseop_description'] ?? $data['lead'] ?? null;
                $article->body = $body ? $converter->convert($body, 'article') : null;

                $article->published_at = $data['active'] ? $data['created_at'] : null;
                $article->created_at = $data['created_at'];

                $article->active = $data['active'];

                $article->save();

                $data['authors'] = array_filter($data['authors']);
                if ($data['authors']) {
                    $authors = User::whereIn('slug', $data['authors'])->get();
                    $article->authors()->sync($authors);
                }

                $data['tags'] = array_filter($data['tags']);
                if ($data['tags']) {
                    $tags = Tag::whereIn('slug', $data['tags'])->get();
                    $article->tags()->sync($tags);
                }

                $data['categories'] = array_filter($data['categories']);
                if ($data['categories']) {
                    $categories = Category::whereIn('slug', $data['categories'])->get();
                    $article->categories()->sync($categories);
                }
            } catch (\Throwable $e) {
                $this->info("\nException: " . $e->getMessage());
            } finally {
                $progress->advance();
            }
        });

        $progress->finish();
        $this->info("\nImporting is finished. Number of skipped records: " . $skipped);

        return 0;
    }
}

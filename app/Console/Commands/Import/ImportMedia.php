<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverter;
use App\Helpers\Import\TimestampToDateConverter;
use App\Helpers\ImportImage;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Console\Command;

class ImportMedia extends Command
{
    private ImportImage $save_image;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:media
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

    public function handle(MediaXMLReader $articleXMLReader, HtmlToEditorJsConverter $converter, TimestampToDateConverter $timeconverter)
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($articleXMLReader->count($path));
        $progress->start();

        $articleXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress, $timeconverter) {
            $article = Article::where('id', $data['id'])->first() ?? new Article();

            if ($article->exists) {
                if (!$deleteIfExist) {
                    $skipped++;
                    $progress->advance();
                    return;
                }
                $article->delete();
                $article = new Article();
            }

            $body = ($data['body_autolink'] != 0) ? $data['body_autolink'] : $data['body'];

            try {
                $imageId = null;
                if ($data['image'] !== '') {
                    $imageId = $this->save_image->saveImage($data['image'], 'articles');
                }
                if ($data['created'] == 0 && $data['modified'] != 0) {
                    $data['created'] = $data['modified'];
                } else if ($data['created'] == 0 && $data['modified'] == 0) {
                    $data['created'] = $data['valid_from'];
                    $data['modified'] = $data['valid_from'];
                } else if ($data['valid_from'] == 0) {
                    $data['valid_from'] = $data['modified'];
                }

                $lectorId = Author::whereName($data['lecturer'])->value('id');

                $article->id = $data['id'];
                $article->legacy_id = $data['legacy_id'];
                $article->author_id = (($data['author_id'] === 0) || $data['author_id'] == 309) ? 743 : $data['author_id'];      //Default "Hazipatika" author; 309 is a deleted author
                $article->lector_id = $lectorId ?? null;
                $article->user_id = 1;
                $article->image_id = $imageId;
                $article->title = $data['title'];
                $article->slug_frozen = true;
                $article->slug = $data['slug'];
                $article->lakmusz_title = $data['subtitle'];
                $article->hirkereso_title = $data['hirkereso_title'];
                $article->lead = $data['lead'];
                $article->body = $converter->convert($body, 'article');
                $article->fb_title = $data['og_title'];
                $article->fb_description = $data['og_description'];
                $article->fb_post = $data['og_post_text'];
                $article->active = $data['status'] === 'e';
                $article->forbidden_update_date = $data['modified_show'];
                $article->campaign = $data['editor_note'];
                $article->adult_content = $data['is_adult'] === 'y';
                $article->noindex = $data['nofollow'] === 'y';
                $article->hidden_from_home = $data['not_show_on_index'] === 'y';
                $article->pr = $data['type'] === 'p';
                $article->branded_content = $data['branded_content'];
                $article->not_share_on_fb = ($data['exclude_from_fbfeed'] !== '') ? $data['exclude_from_fbfeed'] : 1;

                $article->published_at = $timeconverter->convert($data['valid_from']);
                $article->created_at = $timeconverter->convert($data['created']);
                $article->updated_at = $timeconverter->convert($data['modified']);

                $article->save();

                $tags = Tag::whereIn('id', $data['tags'])->get();
                $article->syncTags($tags);

                if (isset($data['category_id'])) {
                    $category = Category
                        ::ofType(Category::TYPE_ARTICLE)
                        ->whereLegacyId($data['category_id'])
                        ->first();

                    if ($category) {
                        $article->categories()->sync($category);
                    }
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

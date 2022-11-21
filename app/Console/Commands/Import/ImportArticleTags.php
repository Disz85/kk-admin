<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverter;
use App\Helpers\Import\TimestampToDateConverter;
use App\Helpers\ImportImage;
use App\Models\Article;
//use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;

use App\XMLReaders\ArticleTagXMLReader;
use App\XMLReaders\ArticleXMLReader;
use Illuminate\Console\Command;

class ImportArticleTags extends Command
{
    private ImportImage $save_image;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:article-tags
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports article tags from XML file';

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

    public function handle(ArticleTagXMLReader $articleXMLReader, HtmlToEditorJsConverter $converter, TimestampToDateConverter $timeconverter)
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($articleXMLReader->count($path));
        $progress->start();

        $articleXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress, $timeconverter) {
            $tag = Tag::where('type', '=', Tag::TYPE_ARTICLE)
                ->where('slug', '=', $data['slug'])
                ->first() ?? new Tag();

            if ($tag->exists) {
                if (!$deleteIfExist) {
                    $skipped++;
                    $progress->advance();
                    return;
                }

                $tag->delete();
                $tag = new Tag();
            }

            try {
                $tag->name = $data['name'];
                $tag->slug = $data['slug'];
                $tag->type = Tag::TYPE_ARTICLE;

                $tag->save();
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

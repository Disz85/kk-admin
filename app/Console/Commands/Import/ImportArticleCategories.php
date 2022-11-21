<?php

namespace App\Console\Commands\Import;

use App\Models\Category;
use App\XMLReaders\ArticleCategoryXMLReader;
use Illuminate\Console\Command;
use App\Helpers\Import\HtmlToEditorJsConverter;

class ImportArticleCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:article-categories
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports article categories from XML file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param ArticleCategoryXMLReader $articleCategoryXMLReader
     * @return int
     */
    public function handle(ArticleCategoryXMLReader $articleCategoryXMLReader, HtmlToEditorJsConverter $converter): int
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($articleCategoryXMLReader->count($path));
        $progress->start();

        $articleCategoryXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress) {
            $article_category = Category::where('type', '=', Category::TYPE_ARTICLE)
                ->where('slug', '=', $data['slug'])
                ->first() ?? new Category();

            if ($article_category->exists) {
                if (!$deleteIfExist) {
                    $skipped++;
                    $progress->advance();
                    return;
                }

                $article_category->delete();
                $article_category = new Category();
            }

            try {
                $article_category->name = $data['name'];
                $article_category->slug = $data['slug'];
                $article_category->type = Category::TYPE_ARTICLE;

                $article_category->save();
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

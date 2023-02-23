<?php

namespace App\Console\Commands\Import;

use App\Enum\CategoryTypeEnum;
use App\Helpers\Import\HtmlToEditorJsConverterIngredient;
use App\Models\Category;
use App\XMLReaders\IngredientCategoryXMLReader;
use Illuminate\Console\Command;

class ImportIngredientCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ingredient-categories
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports ingredient categories from XML file';

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
     */
    public function handle(IngredientCategoryXMLReader $ingredientCategoryXMLReader, HtmlToEditorJsConverterIngredient $converterIngredient): void
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');
        $progress = $this->output->createProgressBar($ingredientCategoryXMLReader->count($path));
        $progress->start();

        $ingredientCategoryXMLReader->read($path, function (array $data) use ($deleteIfExist, &$skipped, $progress, $converterIngredient): void {
            $category = Category::where('slug', '=', $data['slug'])->first() ?? new Category();

            if ($category->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }

                $category->delete();
                $category = new Category();
            }

            try {
                $category->legacy_id = $data['id'];
                $category->name = $data['title'] ?? null;
                $category->slug = $data['slug'] ?? null;
                $category->type = CategoryTypeEnum::Ingredient;
                $category->created_at = $data['creton'] ?? null;
                $category->updated_at = $data['modon'] ?? null;

                if (key_exists('description', $data)) {
                    $category->description = $converterIngredient->convert($data['description'], 'article') ?? null;
                }

                $category->save();
            } catch (\Throwable $e) {
                $this->info("\nException: " . $e->getMessage());
            } finally {
                $progress->advance();
            }
        });

        $progress->finish();

        $this->info("\nImporting is finished. Number of skipped records: " . $skipped);
    }
}

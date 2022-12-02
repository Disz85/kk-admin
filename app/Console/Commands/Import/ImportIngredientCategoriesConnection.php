<?php

namespace App\Console\Commands\Import;

use App\Models\Category;
use App\Models\Ingredient;
use App\XMLReaders\IngredientCategoryConnectionXMLReader;
use Illuminate\Console\Command;

class ImportIngredientCategoriesConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ingredient-categories-connection
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect ingredient and categories from XML file';

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
    public function handle(IngredientCategoryConnectionXMLReader $ingredientCategoryConnectionXMLReader): void
    {
        $path = $this->option('path');
        $progress = $this->output->createProgressBar($ingredientCategoryConnectionXMLReader->count($path));
        $progress->start();

        $ingredientCategoryConnectionXMLReader->read($path, function (array $data) use ($progress) {
            $ingredient = Ingredient::where('legacy_id', '=', $data['ingredientid'])->first();
            $category = Category::where('legacy_id', '=', $data['ingredientfunctionid'])->first();

            try {
                if ($ingredient !== null && $category !== null) {
                    $ingredient->categories()->attach($category);
                } else {
                    $this->info("\nIngredient or Category object is not found:\n Ingredient legacy id = ".$data['ingredientid']. "\n Category legacy id = ".$data['ingredientfunctionid']);
                }
            } catch (\Throwable $e) {
                $this->info("\nException: " . $e->getMessage());
            } finally {
                $progress->advance();
            }
        });

        $progress->finish();

        $this->info("\nImporting is finished.");
    }
}

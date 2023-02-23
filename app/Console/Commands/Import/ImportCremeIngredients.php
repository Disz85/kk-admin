<?php

namespace app\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCremeIngredients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:creme-ingredients
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports creme-ingredient connections from XML file';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = $this->option('path');

        $this->call(ImportXml::class, ['--path' => $path]);

        $this->info("\n Import Cremes ingredients \n");

        $this->importCremeIngredients();

        $this->info("\nProduct ingredient connection import is finished.");

        $this->call(DropXmlTable::class, [ '--path' => $path ]);
    }

    private function importCremeIngredients(): void
    {
        DB::unprepared("
            INSERT INTO product_ingredient (
                product_id,
                ingredient_id,
                `order`,
                created_at,
                updated_at
            )
            SELECT
                products.id,
                ingredients.id,
                TRIM(_tmp_CremeContainsIngredient.Ordinal),
                NOW(),
                NOW()
            FROM _tmp_CremeContainsIngredient
            INNER JOIN products ON _tmp_CremeContainsIngredient.CremeID = products.legacy_id
            INNER JOIN ingredients ON _tmp_CremeContainsIngredient.IngredientID = ingredients.id
            ON DUPLICATE KEY UPDATE
                `order` = VALUES(`order`),
                created_at = VALUES(created_at),
                updated_at = VALUES(updated_at);
        ");
    }
}

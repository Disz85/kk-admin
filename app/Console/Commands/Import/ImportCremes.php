<?php

namespace app\Console\Commands\Import;

use App\Jobs\ConvertHtmlToEditorJs;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCremes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cremes
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports cremes from XML file';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = $this->option('path');

        $this->call(ImportXml::class, ['--path' => $path]);

        $this->info("\n Import Cremes to products table \n");

        $this->importCremesFast();
        $this->updateProductUsers();
        $this->importCremeImages();
        $this->convertHtmlToEditorJs();

        $this->info("\nProduct importing is finished.");

        $this->call(DropXmlTable::class, [ '--path' => $path ]);
    }

    private function importCremesFast()
    {
        DB::unprepared("
            INSERT INTO products (
                `legacy_id`,
                `name`,
                `canonical_name`,
                `slug`,
                `legacy_description`,
                `legacy_image_url`,
                `price`,
                `size`,
                `where_to_find`,
                `brand_id`,
                `active`,
                `sponsored`,
                `created_at`,
                `legacy_created_by`,
                `updated_at`,
                `legacy_updated_by`,
                `published_at`
            )
            SELECT
                TRIM(_tmp_Cremes.Id),
                TRIM(_tmp_Cremes.Title),
                TRIM(_tmp_Cremes.Title),
                TRIM(_tmp_Cremes.Slug),
                TRIM(_tmp_Cremes.Description),
                TRIM(_tmp_Cremes.PictureURL),
                TRIM(_tmp_Cremes.Price),
                TRIM(_tmp_Cremes.Size),
                TRIM(_tmp_Cremes.WhereToFind),
                TRIM(brands.id),
                TRIM(_tmp_Cremes.IsApproved),
                TRIM(_tmp_Cremes.IsPromoted),
                TRIM(_tmp_Cremes.CretOn),
                TRIM(_tmp_Cremes.CretBy),
                TRIM(_tmp_Cremes.ModOn),
                TRIM(_tmp_Cremes.ModBy),
                TRIM(_tmp_Cremes.ModOn)
            FROM _tmp_Cremes INNER JOIN brands ON brands.legacy_id = _tmp_Cremes.BrandID
            ON DUPLICATE KEY UPDATE
                legacy_id = VALUES(legacy_id),
                canonical_name = VALUES(canonical_name),
                legacy_description = VALUES(legacy_description),
                legacy_image_url = VALUES(legacy_image_url),
                price = VALUES(price),
                size = VALUES(size),
                where_to_find = VALUES(where_to_find),
                brand_id = VALUES(brand_id),
                active = VALUES(active),
                sponsored = VALUES(sponsored),
                created_at = VALUES(created_at),
                legacy_created_by = VALUES(legacy_created_by),
                updated_at = VALUES(updated_at),
                legacy_updated_by = VALUES(legacy_updated_by),
                published_at = VALUES(published_at);
        ");
    }

    private function updateProductUsers()
    {
        $this->info("Update created_by, updated_by from users to products.");
        DB::statement("
            UPDATE products as po
            LEFT JOIN users as u ON po.legacy_created_by = u.username
            SET po.created_by = u.id;
        ");
        DB::statement("
            UPDATE products as po
            LEFT JOIN users as u ON po.legacy_updated_by = u.username
            SET po.updated_by = u.id;
        ");
    }

    private function importCremeImages()
    {
        Product::query()
            ->whereNull('image_id')
            ->whereRaw('LEFT(legacy_image_url, 5) <> "https"')
            ->eachById(function ($product) {
                \App\Jobs\ImportImage::dispatch($product)->onQueue('image');
            });
    }

    private function convertHtmlToEditorJs()
    {
        Product::query()
            ->whereNotNull('legacy_description')
            ->whereNull('description')
            ->eachById(function ($product) {
                ConvertHtmlToEditorJs::dispatch($product)->onQueue('editorjs');
            });
    }
}

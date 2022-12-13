<?php

namespace App\Console\Commands\Import;

use App\Helpers\ImportImage as ImageHelper;
use App\Jobs\ConvertHtmlToEditorJs;
use App\Jobs\ImportImage;
use App\Models\Article;
use App\Models\Brand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:brands
                            {--path=* : The path(s) of the XML file(s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import brands from XML files';

    /**
     * Create a new command instance.
     */
    public function __construct(private ImageHelper $imageHelper)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paths = $this->option('path');

        foreach ($paths as $path) {
            $this->call(ImportXML::class, [ '--path' => $path ]);
        }

        $this->importBrands();
        $this->importBrandTags();
        $this->importBrandTagRelations();
        $this->importBrandImages();
        $this->convertHtmlToEditorJs();

        foreach ($paths as $path) {
            $this->call(DropXmlTable::class, [ '--path' => $path ]);
        }
    }

    private function importBrands()
    {
        DB::unprepared("
            INSERT INTO brands (
                legacy_id,
                title,
                slug,
                url,
                legacy_description,
                legacy_image_url,
                where_to_find,
                approved,
                created_at,
                updated_at
            )
            SELECT
                TRIM(_tmp_Brands.Id),
                TRIM(_tmp_Brands.Title),
                TRIM(_tmp_Brands.Slug),
                TRIM(_tmp_Brands.Link),
                TRIM(_tmp_Brands.Description),
                TRIM(_tmp_Brands.PictureURL),
                TRIM(_tmp_Brands.WhereToFind),
                TRIM(_tmp_Brands.ModOn),
                TRIM(_tmp_Brands.CretOn),
                TRIM(_tmp_Brands.ModOn)
            FROM _tmp_Brands;
        ");
    }

    private function importBrandTags()
    {
        DB::unprepared("
            INSERT INTO tags (
                legacy_id,
                name,
                slug,
                description,
                created_at,
                updated_at
            )
            SELECT
                TRIM(_tmp_BrandTags.Id),
                TRIM(_tmp_BrandTags.Title),
                TRIM(_tmp_BrandTags.Slug),
                TRIM(TRAILING '|' FROM CONCAT_WS('|', TRIM(_tmp_BrandTags.Plural), TRIM(IFNULL(_tmp_BrandTags.Description, '')))),
                TRIM(_tmp_BrandTags.CretOn),
                TRIM(_tmp_BrandTags.ModOn)
            FROM _tmp_BrandTags
            ON DUPLICATE KEY UPDATE
                description = VALUES(description),
                created_at = VALUES(created_at),
                updated_at = VALUES(updated_at);
        ");
    }

    private function importBrandTagRelations()
    {
        DB::unprepared("
            INSERT INTO taggables (
                tag_id,
                taggable_id,
                taggable_type
            )
            SELECT
                tags.id,
                brands.id,
                'App\\\Models\\\Brand'
            FROM _tmp_Brands_BrandTags
            INNER JOIN tags ON _tmp_Brands_BrandTags.BrandTagsID = tags.legacy_id
            INNER JOIN brands ON _tmp_Brands_BrandTags.BrandID = brands.legacy_id
            ORDER BY _tmp_Brands_BrandTags.BrandID;
        ");
    }

    private function importBrandImages()
    {
        Brand::query()
            ->whereNull('image_id')
            ->whereRaw('LEFT(legacy_image_url, 5) <> "https"')
            ->eachById(function ($brand) {
                ImportImage::dispatch($brand)->onQueue('image');
            });
    }

    private function convertHtmlToEditorJs()
    {
        Brand::query()
            ->whereNotNull('legacy_description')
            ->whereNull('description')
            ->eachById(function ($brand) {
                ConvertHtmlToEditorJs::dispatch($brand)->onQueue('editorjs');
            });
    }
}

<?php

namespace App\Console\Commands\Import;

use App\Jobs\ConvertHtmlToEditorJs;
use App\Jobs\ImportImage;
use App\Models\ProductOffer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCreamOffers extends Command
{
    private const TMP_TABLE = "_tmp_CreamOffers";
    private const PRODUCT_OFFERS_TABLE = "product_offers";
    private const USERS_TABLE = "users";
    private const PRODUCTS_TABLE = "products";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "import:cream-offers
                            {--path= : The path of the XML file}";

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
     *
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = $this->option("path");

        $this->importProductOffers($path);
        $this->updateForeignKeys();
        $this->importImages();
        $this->convertHtmlToEditorJs();

        $this->info("\n\nProduct offer (productOffers table) import is finished.");

        $this->call(DropXmlTable::class, ['--path' => $path]);

        return 0;
    }

    /**
     * @param string $path
     * @return void
     */
    private function importProductOffers(string $path)
    {
        $this->call(ImportXML::class, ['--path' => $path]);

        $this->info("Stage 1/4: import data from ".self::TMP_TABLE." to ".self::PRODUCT_OFFERS_TABLE."..");
        DB::statement("
            INSERT INTO ".self::PRODUCT_OFFERS_TABLE."
            (legacy_id, name, legacy_description, price, used, place, shipping_payment,bought_at, created_at, is_sold, is_approved, slug, legacy_image_url, legacy_product_id, legacy_created_by, legacy_bought_by)
            SELECT tpo.Id, tpo.Title, tpo.Description, tpo.Price, tpo.Used, tpo.Place, tpo.ShippingPayment, tpo.BoughtOn, tpo.CretOn, tpo.IsSold, IF(tpo.IsPending=0, 1, 0), tpo.Slug, tpo.PictureURL, tpo.CreamId, tpo.CretBy, tpo.BoughtBy
            FROM ".self::TMP_TABLE." as tpo
            ORDER BY tpo.Id ASC;
        ");
    }

    /**
     * @return void
     */
    private function updateForeignKeys()
    {
        $this->info("Stage 2/4: update foreign key from ".self::USERS_TABLE." to ".self::PRODUCT_OFFERS_TABLE."..");
        DB::statement("
            UPDATE ".self::PRODUCT_OFFERS_TABLE." as po
            LEFT JOIN ".self::USERS_TABLE." as u ON po.legacy_bought_by = u.username
            SET po.bought_by = u.id;
        ");
        DB::statement("
            UPDATE ".self::PRODUCT_OFFERS_TABLE." as po
            LEFT JOIN ".self::USERS_TABLE." as u ON po.legacy_created_by = u.username
            SET po.created_by = u.id;
        ");

        $this->info("Stage 3/4: update foreign key from ".self::PRODUCTS_TABLE." to ".self::PRODUCT_OFFERS_TABLE."..");
        DB::statement("
            UPDATE ".self::PRODUCT_OFFERS_TABLE." as po
            SET po.product_id = (SELECT id FROM ".self::PRODUCTS_TABLE." as p WHERE po.legacy_product_id = p.legacy_id);
        ");
    }

    /**
     * @return void
     */
    private function importImages()
    {
        $this->info("Stage 4/4: insert image's url path to media..");
        ProductOffer::query()
            ->whereNull('image_id')
            ->whereRaw('LEFT(legacy_image_url, 5) <> "https"')
            ->eachById(function ($productOffer) {
                ImportImage::dispatch($productOffer)->onQueue('image');
            });
    }

    /**
     * @return void
     */
    private function convertHtmlToEditorJs()
    {
        ProductOffer::query()
            ->whereNotNull('legacy_description')
            ->whereNull('description')
            ->eachById(function ($brand) {
                ConvertHtmlToEditorJs::dispatch($brand)->onQueue('editorjs');
            });
    }
}

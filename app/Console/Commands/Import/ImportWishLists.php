<?php

namespace app\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportWishLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:wish-lists
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports wish lists to shelves from XML file';

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
    public function handle(): void
    {
        $path = $this->option('path');

        $this->call(ImportXml::class, ['--path' => $path]);

        $this->importShelves();
        $this->importProductShelf();

        $this->info("\nWishlist importing is finished.");

        $this->call(DropXmlTable::class, ['--path' => $path]);
    }

    private function importShelves()
    {
        DB::unprepared("
            INSERT INTO shelves (
                title,
                slug,
                is_private,
                user_id,
                created_at,
                updated_at
            )
            SELECT
                'Kívánságlista',
                'kivansaglista',
                1,
                users.id,
                NOW(),
                NOW()
            FROM _tmp_Wishlist
            INNER JOIN users ON users.legacy_nickname = _tmp_Wishlist.NickName
            ON DUPLICATE KEY UPDATE
                is_private = VALUES(is_private),
                created_at = VALUES(created_at),
                updated_at = VALUES(updated_at);
        ");
    }

    private function importProductShelf()
    {
        DB::unprepared("
            REPLACE INTO product_shelf (
                product_id,
                shelf_id,
                created_at,
                updated_at
            )
            SELECT
                products.id,
                shelves.id,
                NOW(),
                NOW()
            FROM _tmp_Wishlist
            INNER JOIN products ON _tmp_Wishlist.CremeId = products.legacy_id
            INNER JOIN users ON _tmp_Wishlist.NickName = users.legacy_nickname
            INNER JOIN shelves ON shelves.user_id = users.id;
        ");
    }
}

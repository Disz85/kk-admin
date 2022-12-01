<?php

namespace app\Console\Commands\Import;

use App\Models\Product;
use App\Models\Shelf;
use App\Models\User;
use App\XMLReaders\WishListXMLReader;
use Illuminate\Console\Command;

class ImportWishLists extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:wish-lists
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

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
    public function handle(WishListXMLReader $wishListXMLReader): void
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($wishListXMLReader->count($path));
        $progress->start();

        $wishListXMLReader->read($path, function (array $data) use ($deleteIfExist, &$skipped, $progress) {
            $shelf = Shelf::leftJoin('users', 'users.id', '=', 'shelves.user_id')->where(['legacy_nickname' => $data['NickName']])
                    ->first() ?? new Shelf();

            if ($shelf->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }

                $shelf->delete();
                $shelf = new Shelf();
            }

            $user = User::where(['legacy_nickname' => $data['NickName']])->first();

            if($user){
                $shelf->user_id = $user->id;
                $shelf->title = 'Kívánságlista';
                $shelf->is_private = true;

                if($shelf->save()){
                    $product = Product::where(['legacy_id' => $data['CremeId']])->first();
                    $shelf->products()->sync($product->id, false);
                }
            }

            $progress->advance();
        });

        $progress->finish();

        $this->info("\nImporting is finished. Number of skipped records: " . $skipped);
    }
}

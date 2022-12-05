<?php

namespace app\Console\Commands\Import;

use App\Models\Product;
use App\XMLReaders\CremeXMLReader;
use Illuminate\Console\Command;

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
    public function handle(CremeXMLReader $cremeXMLReader): void
    {
        $path = $this->option('path');

        $progress = $this->output->createProgressBar($cremeXMLReader->count($path));
        $progress->start();

        $cremeXMLReader->read($path, function (array $data) use ($progress) {
            $product = Product::where(['legacy_id' => $data['Id']])->first() ?? new Product();

            $product->legacy_id = $data['Id'];
            $product->name = $data['Title'];
            $product->slug = $data['Slug'];
            $product->brand_id = 1;
            $product->active = $data['IsApproved'];
            $product->description = array_key_exists('Description', $data) ? trim($data['Description']) : '';
            $product->created_at = $data['CretOn'];
            $product->updated_at = $data['ModOn'];
            $product->save();

            $progress->advance();
        });

        $progress->finish();

        $this->info("\nImporting is finished. Number of skipped records.");
    }
}

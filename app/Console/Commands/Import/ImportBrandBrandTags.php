<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverterMagazine;
use App\Helpers\Import\TimestampToDateConverter;
use App\Models\Brand;
use App\Models\Tag;
use App\XMLReaders\BrandBrandTagXMLReader;
use Illuminate\Console\Command;

class ImportBrandBrandTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:brand-brand-tags
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports brand - tags connections from XML file';

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
     * @return int
     */
    public function handle(BrandBrandTagXMLReader $brandBrandTagXMLReader, HtmlToEditorJsConverterMagazine $converter, TimestampToDateConverter $timeconverter)
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($brandBrandTagXMLReader->count($path));
        $progress->start();

        $brandBrandTagXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress, $timeconverter) {
            $brand = Brand::where('legacy_id', '=', $data['brand_id'])
                ->first() ?? new Brand();

            $tag = Tag::where('legacy_id', '=', $data['tag_id'])
                ->first() ?? new Tag();

            try {
                $brand->tags()->attach($tag);
                $tag->save();
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

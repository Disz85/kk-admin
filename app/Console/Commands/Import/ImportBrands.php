<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverterMagazine;
use App\Helpers\Import\TimestampToDateConverter;
use App\Helpers\ImportImage;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;

use App\Models\User;
use App\XMLReaders\BrandXMLReader;
use Illuminate\Console\Command;

class ImportBrands extends Command
{
    private ImportImage $save_image;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:brands
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports brands from XML file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImportImage $save_image)
    {
        parent::__construct();
        $this->save_image = $save_image;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(BrandXMLReader $brandXMLReader, HtmlToEditorJsConverterMagazine $converter, TimestampToDateConverter $timeconverter)
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($brandXMLReader->count($path));
        $progress->start();

        $brandXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress, $timeconverter) {
            $brand = Brand::where('legacy_id', '=', $data['id'])->first() ?? new Brand();

            if ($brand->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }
                $brand->delete();
                $brand = new Brand();
            }

            $description = $data['description'] ?? null;

            try {
                $brand->legacy_id = $data['id'] ?? null;

                $brand->title = $data['title'];
                $brand->slug = $data['slug'];
                $brand->description = $description ? $converter->convert($description, 'article') : $description;

                $imageId = Media::query()
                    ->orderBy('id', 'DESC')
                    ->first()
                    ->id ?? null;

                $brand->image_id = $imageId ?? null;

                $brand->created_at = $data['created_at'] ?? null;
                $brand->updated_at = $data['updated_at'] ?? null;

                $brand->url = $data['url'] ?? null;
                $brand->where_to_find = $data['where_to_find'] ?? null;

                $brand->approved = $data['approved'];

//                if ($data['created_by']) {
//                    $brand->created_by = User::whereIn('username', $data['created_by'])->first()->id;
//                }

//                if ($data['updated_by']) {
//                    $brand->updated_by = User::whereIn('username', $data['updated_by'])->first()->id;
//                }

                $brand->save();

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

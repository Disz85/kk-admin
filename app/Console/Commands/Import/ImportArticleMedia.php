<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverterMagazine;
use App\Helpers\Import\TimestampToDateConverter;
use App\Helpers\ImportImage;
use App\Models\Media;
use App\XMLReaders\ArticleMediaXMLReader;
use Illuminate\Console\Command;

class ImportArticleMedia extends Command
{
    private ImportImage $save_image;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:article-media
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports articles from XML file';

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
    public function handle(ArticleMediaXMLReader $articleMediaXMLReader, HtmlToEditorJsConverterMagazine $converter, TimestampToDateConverter $timeconverter)
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($articleMediaXMLReader->count($path));
        $progress->start();

        $articleMediaXMLReader->read($path, function (array $data) use ($converter, $deleteIfExist, &$skipped, $progress, $timeconverter) {
            $media = Media::where('legacy_id', '=', $data['legacy_id'])->first();

            if ($media) {
                return;
            }

            try {
                $imageId = $this->save_image->saveImage($data['image'], 'articles');

                if (! $imageId) {
                    return;
                }

                $media = Media::find($imageId);

                $media->title = $data['title'] ?? null;
                $media->legacy_id = $data['legacy_id'];
                $media->legacy_url = $data['image'];
                $media->save();
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

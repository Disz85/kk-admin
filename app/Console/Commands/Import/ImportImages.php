<?php

namespace App\Console\Commands\Import;

use App\Helpers\ImportImage as ImageHelper;
use Illuminate\Console\Command;

class ImportImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:images
                            {--url=* : The url(s) of the image(s)}
                            {--type : The type of the resource for what the image belongs to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import image';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private ImageHelper $save_image)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $urls = $this->option('url');
        $type = $this->option('type');

        foreach ($urls as $url) {
            $this->info($this->save_image->saveImage($url, $type));
        }
    }
}

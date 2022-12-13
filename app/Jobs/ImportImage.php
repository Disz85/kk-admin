<?php

namespace App\Jobs;

use App\Helpers\ImportImage as ImageHelper;
use App\Models\Media;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportImage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Model $model)
    {
        $this->model = $model->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageHelper $imageHelper)
    {
        $remoteRootFolder = match ($this->model->getTable()) {
            'product_offers' => 'UploadedMakeUps',
            default => 'UploadedImages',
        };

        try {
            $legacyImageUrl = $imageHelper->getLegacyImageUrl(
                $remoteRootFolder,
                strtolower($this->model->legacy_image_url)
            );

            $imageId = Media::query()
                ->where('legacy_url', '=', $legacyImageUrl)
                ->first()
                ->id ?? null;

            if (str_contains($legacyImageUrl, 'http') && ! $imageId) {
                $imageId = $imageHelper->saveImage($legacyImageUrl, $this->model->getTable());
            }

            $this->model->legacy_image_url = $legacyImageUrl;
            $this->model->image_id = $imageId;
            $this->model->timestamps = false;
            $this->model->save();
        } catch (Exception $exception) {
            $this->fail($exception);
        }
    }
}

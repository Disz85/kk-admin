<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\Admin\MediaResource;
use App\Models\Media;
use App\Repositories\MediaRepositoryInterface;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Throwable;

class MediaController extends Controller
{
    private MediaRepositoryInterface $mediaRepository;

    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * @param StoreMediaRequest $request
     * @return MediaResource
     */
    public function upload(StoreMediaRequest $request): MediaResource
    {
        //$request->validated();

        $file = $request->file('media');
        $path = implode('/', array_filter(['images', $request->get('resource')]));

        $path = $this->mediaRepository->storeWithPrefix(new File($file->path()), $path);

        $media = new Media(['path' => $path, 'type' => $file->getClientMimeType()]);
        $media->save();

        return new MediaResource($media);
    }

    /**
     * @param Media $media
     * @return void
     * @throws Throwable
     */
    public function delete(Media $media)
    {
        DB::transaction(function () use ($media) {
            $this->mediaRepository->delete($media->path);
            $media->delete();
        });
    }
}

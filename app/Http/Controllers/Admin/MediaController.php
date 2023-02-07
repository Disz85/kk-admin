<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\Admin\MediaCollection;
use App\Http\Resources\Admin\MediaResource;
use App\Models\Media;
use App\Repositories\MediaRepositoryInterface;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
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
        $file = $request->file('media');
        $path = implode('/', array_filter(['images', $request->get('resource')]));

        $path = $this->mediaRepository->storeWithPrefix(new File($file->path()), $path);

        $media = new Media(['path' => $path, 'type' => $file->getClientMimeType()]);
        $media->save();

        return new MediaResource($media);
    }

    /**
     * @param Request $request
     * @return MediaCollection
     * @throws Throwable
     */
    public function uploadMultiple(Request $request): MediaCollection
    {
        $validated = $request->validate(['media.*' => 'mimes:jpeg,png,gif']);
        $resource = $request->get('resource');

        $files = DB::transaction(function () use ($validated, $resource) {
            $files = collect();

            /** @var UploadedFile $file */
            foreach (Arr::get($validated, 'media') as $file) {
                $path = implode('/', array_filter(['images', $resource]));
                $path = $this->mediaRepository->storeWithPrefix(new File($file->path()), $path);
                $media = new Media(['path' => $path, 'type' => $file->getClientMimeType()]);
                $files->add($media);
                $media->save();
            }

            return $files;
        });

        return new MediaCollection($files);
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

    /**
     * @param Request $request
     * @return void
     * @throws Throwable
     */
    public function deleteMultiple(Request $request)
    {
        $mediaModels = Media::findMany($request->all());
        DB::transaction(function () use ($mediaModels) {
            foreach ($mediaModels as $media) {
                $this->mediaRepository->delete($media->path);
                $media->delete();
            }
        });
    }
}

<?php

namespace App\Helpers;

use App\Repositories\MediaRepositoryInterface;
use Exception;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Http\File;
use App\Models\Media;

class ImportImage
{
    private MediaRepositoryInterface $mediaRepository;

    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    public function saveImage(mixed $imageUrl, string $resource, bool $isImport = false)
    {
        try {
            $tempPath = storage_path('tmp/uploads/');
            $fileInfo = pathinfo($imageUrl);
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0777, true);
            }
            $this->downloadFile($tempPath, $imageUrl, $fileInfo['basename']);
            $mimetypes = MimeType::fromExtension($fileInfo['extension']);
            if ($mimetypes == '' || $mimetypes == '?') {
                $mimetypes = 'image/jpeg';
            }

            $path = implode('/', array_filter(['images', $resource]));
            $path = $this->mediaRepository->storeWithPrefix(new File($tempPath . '/' . $fileInfo['basename']), $path);

            $media = new Media(['path' => $path, 'type' => $mimetypes]);
            $media->save();
            $this->deleteFile($tempPath . '/' . $fileInfo['basename']);

            if ($isImport) {
                return $media->path;
            } else {
                return $media->id;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function saveImage2(mixed $imageUrl, string $resource)
    {
        $tempPath = storage_path('tmp/uploads/');
        $fileInfo = pathinfo($imageUrl);
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }
        $this->downloadFile($tempPath, $imageUrl, $fileInfo['basename']);
        $mimetypes = MimeType::fromExtension($fileInfo['extension']);
        if ($mimetypes == '' || $mimetypes == '?') {
            $mimetypes = 'image/jpeg';
        }

        $path = implode('/', array_filter(['images', $resource]));
        $path = $this->mediaRepository->storeWithPrefix(new File($tempPath . '/' . $fileInfo['basename']), $path);

        $media = new Media(['path' => $path, 'type' => $mimetypes]);
        $media->save();
        $this->deleteFile($tempPath . '/' . $fileInfo['basename']);

        return $media;
    }

    private function downloadFile(string $path, string $url, string $filename): void
    {
        $remote_stream = fopen($url, 'r');

        if (!$remote_stream || !file_put_contents($path . $filename, $remote_stream)) {
            throw new Exception('Failed to download.');
        }
    }

    private function deleteFile(mixed $path): void
    {
        if ($path) {
            unlink($path);
        }
    }

}

<?php

namespace App\Repositories;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaRepository implements MediaRepositoryInterface
{
    /** @var string */
    private string $disk;

    public function __construct()
    {
        $this->disk = config('file.disk');
    }

    /**
     * @inheritDoc
     */
    public function store(File $file, string $path, string $name = null, array $meta = []): string
    {
        return Storage::disk($this->disk)->putFileAs($path, $file, $name ?? $file->hashName(), $meta);
    }

    /**
     * Downloads the resource found under the given URL to the path specified or /tmp otherwise
     * @param string $url
     * @param string|null $path
     * @param string|null $username
     * @param string|null $password
     * @return File
     */
    public function download(string $url, string $path = null, ?string $username = '', ?string $password = ''): File
    {
        if ($username || $password) {
            $cred = sprintf(
                'Authorization: Basic %s',
                base64_encode($username . ":" . $password)
            );

            $context = stream_context_create([
                'http' => [
                    'header' => $cred,
                ],
            ]);
        }

        $filename = 'media-repository-temporary.file';
        $filePath = $path ?? tempnam(sys_get_temp_dir(), $filename);
        copy($url, $filePath, $context ?? null);

        return new File($filePath);
    }

    /**
     * @param string $path
     * @return false|mixed|resource|null
     */
    public function stream(string $path): mixed
    {
        return Storage::disk($this->disk)->readStream($path);
    }

    /**
     * @inheritDoc
     */
    public function storeWithPrefix(File $file, string $path, string $name = null, array $meta = []): string
    {
        return $this->store($file, $this->getPathString($path), $name, $meta);
    }

    /**
     * @inheritDoc
     */
    public function writeStream(string $path, string $tmpPath): bool
    {
        return Storage::disk($this->disk)->writeStream($path, fopen($tmpPath, 'rb'));
    }

    /**
     * @inheritDoc void
     */
    public function delete(array|string $paths): bool
    {
        return Storage::disk($this->disk)->delete($paths);
    }

    private function getPathString(string $path): string
    {
        return $path . '/' . Str::random(2) . '/' . Str::random(2);
    }

    /**
     * @inheritDoc
     */
    public function get(string $path): mixed
    {
        return Storage::disk($this->disk)->get($path);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, $contents, $options = []): bool
    {
        return Storage::disk($this->disk)->put($path, $contents, $options);
    }

    /**
     * @inheritDoc
     */
    public function has(string $path): bool
    {
        return Storage::disk($this->disk)->has($path);
    }
}

<?php

namespace App\Repositories;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\File;

interface MediaRepositoryInterface
{
    /**
     * @param File $file
     * @param string $path
     * @param string|null $name
     * @param array $meta
     * @return string
     */
    public function store(File $file, string $path, string $name = null, array $meta = []): string;

    /**
     * @param File $file
     * @param string $path
     * @param string|null $name
     * @param array $meta
     * @return string
     */
    public function storeWithPrefix(File $file, string $path, string $name = null, array $meta = []): string;

    /**
     * @param string $path
     * @param string $tmpPath
     * @return bool
     */
    public function writeStream(string $path, string $tmpPath): bool;

    /**
     * @param string $path
     * @return mixed
     */
    public function stream(string $path): mixed;

    /**
     * @param array|string $paths
     * @return boolean
     */
    public function delete(array|string $paths): bool;

    /**
     * @param string $path
     * @return mixed
     * @throws FileNotFoundException
     */
    public function get(string $path): mixed;

    /**
     * @param string $path
     * @param $contents
     * @param array $options
     * @return bool
     */
    public function put(string $path, $contents, array $options = []): bool;

    /**
     * @param string $path
     * @return bool
     */
    public function has(string $path): bool;
}

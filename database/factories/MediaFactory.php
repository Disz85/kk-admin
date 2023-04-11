<?php

namespace Database\Factories;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Database\Seeders\ImageSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'width' => $this->faker->numberBetween(200, 500),
            'height' => $this->faker->numberBetween(200, 250),
            'path' => fn (array $data) => $this->faker->imageUrl($data['width'], $data['height']),
            'type' => 'image/png',
            'title' => $this->faker->words(1, 3),
        ];
    }

    public function withRealImage(string $path = null): self
    {
        $path = $path ?? $this->randomImage();
        $uploadedPath = $this->uploadImageToStorage($path);

        return $this->state([
            'path' => $uploadedPath,
        ]);
    }

    private function randomImage(): string
    {
        $files = glob(storage_path('/images') . '/*.*');

        if (! $files) {
            (app()->make(ImageSeeder::class))->run();
            $files = glob(storage_path('/images') . '/*.*');
        }

        $file = array_rand($files);

        return $files[$file];
    }

    private function uploadImageToStorage(string $path): string
    {
        /** @var MediaRepository $mediaRepository */
        $mediaRepository = app()->make(MediaRepository::class);

        return $mediaRepository->store(new File($path), '/images');
    }
}

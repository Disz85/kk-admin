<?php

namespace Database\Helpers;

use Faker\Generator;

class BlockStyleEditorFakeContentBuilder
{
    /** @var array<int, mixed> */
    protected array $parts;
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
        $this->reset();
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        $body = [
            'time' => now()->timestamp,
            'blocks' => [],
        ];

        foreach ($this->parts as $part) {
            $body['blocks'][] = $part;
        }

        $this->reset();

        return $body;
    }

    public function reset(): void
    {
        $this->parts = [];
    }

    /**
     * @param array<string, mixed> $merge
     * @return $this
     */
    public function addParagraph(array $merge = []): self
    {
        $this->parts[] = $this->generateParagraph($merge);

        return $this;
    }

    /**
     * @param array<string, mixed> $merge
     * @return $this
     */
    public function addHeader(array $merge = []): self
    {
        $this->parts[] = $this->generateHeader($merge);

        return $this;
    }

    /**
     * @param array<string, mixed> $merge
     * @return $this
     */
    public function addList(array $merge = []): self
    {
        $this->parts[] = $this->generateList($merge);

        return $this;
    }

    /**
     * @param array<string, mixed> $merge
     * @return $this
     */
    public function addQuote(array $merge = []): self
    {
        $this->parts[] = $this->generateQuote($merge);

        return $this;
    }

    /**
     * @param array<string, mixed> $merge
     * @return array<string, mixed>
     */
    protected function generateParagraph(array $merge = []): array
    {
        return array_merge([
            'id' => $this->faker->bothify('??????#*#*'),
            'type' => 'paragraph',
            'data' => ['text' => $this->faker->realTextBetween(160, 350)],
        ], $merge);
    }

    /**
     * @param array<string, mixed> $merge
     * @return array<string, mixed>
     */
    protected function generateHeader(array $merge = []): array
    {
        return array_merge([
            'id' => $this->faker->bothify('??????#*#*'),
            'type' => 'header',
            'data' => [
                'text' => $this->faker->words($this->faker->numberBetween(1, 4), true),
                'level' => $this->faker->numberBetween(2, 3),
            ],
        ], $merge);
    }

    /**
     * @param array<string, mixed> $merge
     * @return array<string, mixed>
     */
    protected function generateList(array $merge = []): array
    {
        return array_merge([
            'id' => $this->faker->bothify('??????#*#*'),
            'type' => 'list',
            'data' => [
                'style' => 'ordered',
                'items' => call_user_func(function () {
                    $arraySize = $this->faker->numberBetween(3, 5);
                    $listItems = [];
                    for ($i = 0; $i < $arraySize; $i++) {
                        $listItems[] = $this->faker->colorName;
                    }

                    return $listItems;
                }),
            ],
        ], $merge);
    }

    /**
     * @param array<string, mixed> $merge
     * @return array<string, mixed>
     */
    protected function generateQuote(array $merge = []): array
    {
        return array_merge([
            'id' => $this->faker->bothify('??????#*#*'),
            'type' => 'quote',
            'data' => [
                'text' => $this->faker->realText(50),
                'caption' => $this->faker->name,
                'alignment' => 'left',
            ],
        ], $merge);
    }
}

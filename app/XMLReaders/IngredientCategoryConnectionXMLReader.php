<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class IngredientCategoryConnectionXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = "KREMMANIA.dbo.IngredientHasFunctions";

    /**
     * @inheritDoc
     */
    public function count(string $path): int
    {
        return $this->countElements($path, self::PARENT_NODE);
    }

    /**
     * @inheritDoc
     */
    public function read(string $path, callable $callback): void
    {
        $this->reader->open($path);

        $ingredientCategory = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($ingredientCategory);
                    $ingredientCategory = [];
                } else {
                    continue;
                }
            }

            $field = mb_strtolower($this->reader->name);
            $this->reader->read();

            $ingredientCategory[$field] = $this->reader->value;
        }

        $this->reader->close();
    }
}

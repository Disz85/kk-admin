<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class IngredientXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = "KREMMANIA.dbo.Ingredients";
    private const FIELDS_TO_INT = [
        "ewgscore",
        "ewgscoremax",
        "comedogenicindex"
    ];

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

        $ingredient = [];


        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($ingredient);
                    $ingredient = [];
                } else {
                    continue;
                }
            }

            $field = mb_strtolower($this->reader->name);
            $this->reader->read();

            switch ($field) {
                case in_array($field, self::FIELDS_TO_INT):
                    $ingredient[$field] = (int)mb_substr($this->reader->value, 0, 1);

                    break;
                default:
                    $ingredient[$field] = $this->reader->value;
            }
        }

        $this->reader->close();
    }
}

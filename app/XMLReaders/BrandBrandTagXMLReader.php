<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class BrandBrandTagXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'KREMMANIA.dbo.Brands_BrandTags';

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

        $brandTag = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($brandTag);
                    $brandTag = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;

            switch ($field) {
                case 'BrandID':
                    $this->reader->read();
                    $brandTag['brand_id'] = (int) trim($this->reader->value);

                    break;

                case 'BrandTagsID':
                    $this->reader->read();
                    $brandTag['tag_id'] = trim($this->reader->value);

                    break;
            }
        }

        $this->reader->close();
    }
}

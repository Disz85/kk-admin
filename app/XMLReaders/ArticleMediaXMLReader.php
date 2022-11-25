<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class ArticleMediaXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'item';

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

        $media = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($media);
                    $media = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;

            switch ($field) {
                case 'title':
                    $this->reader->read();
                    $media[$field] = trim($this->reader->value);

                    break;
                case 'wp:attachment_url':
                    $this->reader->read();
                    $media['image'] = trim($this->reader->value);

                    break;
                case 'wp:post_id':
                    $this->reader->read();
                    $media['legacy_id'] = trim($this->reader->value);

                    break;
            }
        }

        $this->reader->close();
    }
}

<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class ArticleTagXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'category';
    private const PARENT_NODE_DOMAIN = 'post_tag';

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

        $tag = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE
                    && trim($this->reader->getAttribute('domain')) === self::PARENT_NODE_DOMAIN
                ) {
                    $callback($tag);
                    $tag = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;
            switch ($field) {
                case self::PARENT_NODE:
                    $tag['slug'] = trim($this->reader->getAttribute('nicename'));
                    $this->reader->read();
                    $tag['name'] = trim($this->reader->value);
                    break;
            }
        }

        $this->reader->close();
    }
}

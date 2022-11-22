<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class ArticleCategoryXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'category';
    private const PARENT_NODE_DOMAIN = 'category';

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

        $article_category = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if (
                    $this->reader->name === self::PARENT_NODE
                    && trim($this->reader->getAttribute('domain')) === self::PARENT_NODE_DOMAIN
                ) {
                    $callback($article_category);
                    $article_category = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;
            switch ($field) {
                case self::PARENT_NODE:
                    $article_category['slug'] = trim($this->reader->getAttribute('nicename'));
                    $this->reader->read();
                    $article_category['name'] = trim($this->reader->value);

                    break;
            }
        }
        $this->reader->close();
    }
}

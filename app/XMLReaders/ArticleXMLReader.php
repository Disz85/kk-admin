<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class ArticleXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'item';
    private const META_KEYS = [
        '_thumbnail_id',
        '_aioseop_description',
        '_aioseop_title',
    ];

    private function meta($node)
    {
        $node->reader->read();

        $metaKey = trim($this->reader->value);

        if (in_array($metaKey, ))

        return [ $metaKey => $metaValue ];
    }

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

        $article = [];
        $article['tags'] = [];
        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($article);
                    $article = [];
                    $article['tags'] = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;

            switch ($field) {
                case 'wp:post_id':
                case 'wp:post_date_gmt':
                case 'wp:post_name':
                case 'title':
                case 'link':
                case 'dc:creator':
                case 'content:encoded':
                case 'excerpt:encoded':
                case 'wp:meta_key':
                case 'guid':
                case 'description':
                    $this->reader->read();
                    $article[$field] = trim($this->reader->value);
                    break;
                case 'tag':
                    $this->reader->read();
                    $article['tags'][] = (int)trim($this->reader->getAttribute('id'));
                    break;
                case 'author_id':
                case 'modified_show':
                case 'nofollow':
                case 'branded_content':
                    $this->reader->read();
                    $article[$field] = (int)trim($this->reader->value);
                    break;
            }
        }

        $this->reader->close();
    }
}

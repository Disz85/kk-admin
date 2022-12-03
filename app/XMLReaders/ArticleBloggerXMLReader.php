<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class ArticleBloggerXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'entry';

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
                case 'id':
                    $this->reader->read();
                    preg_match('/(.*)-([0-9]+)/', trim($this->reader->value), $matches);
                    if (isset($matches[2])) {
                        $article['id'] = $matches[2];
                    }

                    break;
                case 'published':
                case 'updated':
                case 'title':
                case 'content':
                case 'description':
                    $this->reader->read();
                    $article[$field] = trim($this->reader->value);

                    break;
                case 'category':
                    if ($this->reader->getAttribute('scheme') == 'http://www.blogger.com/atom/ns#') {
                        $article['tags'][] = $this->reader->getAttribute('term');
                    }
                    if ($this->reader->getAttribute('scheme') == 'http://schemas.google.com/g/2005#kind' && $this->reader->getAttribute('term') == 'http://schemas.google.com/blogger/2008/kind#post') {
                        $article['is_article'] = true;
                    }

                    break;
                case 'link':
                    if ($this->reader->getAttribute('rel') == 'replies') {
                        $article['url'] = str_replace('#comment-form', '', $this->reader->getAttribute('href'));
                        preg_match('/http:\/\/blog\.kremmania\.hu\/([0-9]+)\/([0-9]+)\/(.*)\.html/', $article['url'], $matches);
                        if (isset($matches[3])) {
                            $article['slug'] = $matches[3];
                        }
                    }

                    break;
                case 'author':
                    $this->reader->read();
                    if ($this->reader->name == 'name') {
                        $this->reader->read();
                        $article['author'] = $this->reader->value ;
                    }

                    break;
                case 'media:thumbnail':
                    $article['thumbnail'] = $this->reader->getAttribute('url');

                    break;
            }
        }

        $this->reader->close();
    }
}

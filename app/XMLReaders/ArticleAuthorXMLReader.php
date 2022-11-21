<?php
namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class ArticleAuthorXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = "wp:author";

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

        $author = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($author);
                    $author = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;
            switch ($field) {
                case 'wp:author_id':
                case 'wp:author_email':
                case 'wp:author_first_name':
                case 'wp:author_last_name':
                    $this->reader->read();
                    $key = str_replace([ 'wp:author_', '_' ], '', $field);
                    $author[$key] = trim($this->reader->value);
                    break;
                case 'wp:author_display_name':
                    $this->reader->read();
                    $author['username'] = trim($this->reader->value);
                    break;
            }
        }

        $this->reader->close();
    }
}

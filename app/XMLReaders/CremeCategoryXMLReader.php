<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use Carbon\Carbon;
use XMLReader;

class CremeCategoryXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'KREMMANIA.dbo.CremeTags';

    /**
     * @inheritDoc
     */
    public function count(string $path): int
    {
        return $this->countElements($path, self::PARENT_NODE);
    }

    public function read(string $path, callable $callback): void
    {
        $this->reader->open($path);
        $category = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($category);
                    $category = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;

            switch ($field) {
                case 'Id':
                    $this->reader->read();
                    $category['id'] = trim($this->reader->value);
                    break;
                case 'Title':
                case 'Description':
                case 'Slug':
                    $this->reader->read();
                    $category[strtolower($field)] = trim($this->reader->value) ?? null;
                    break;
                case 'CretOn':
                    $this->reader->read();

                    $date = trim($this->reader->value);

                    $format = 'Y-m-d\TH:i:s';
                    if (str_contains($date, '.')) {
                        $format = 'Y-m-d\TH:i:s.u';
                    }

                    $date = Carbon::createFromFormat($format, $date);
                    $category['created_at'] = $date->format('Y-m-d H:i:s');

                    break;

                case 'ModOn':
                    $this->reader->read();

                    $date = trim($this->reader->value);

                    $format = 'Y-m-d\TH:i:s';
                    if (str_contains($date, '.')) {
                        $format = 'Y-m-d\TH:i:s.u';
                    }

                    $date = Carbon::createFromFormat($format, $date);
                    $category['updated_at'] = $date->format('Y-m-d H:i:s');

                    break;

                case 'CretBy':
                    $this->reader->read();
                    $category['created_by'] = trim($this->reader->value);
                    break;

                case 'ModBy':
                    $this->reader->read();
                    $category['updated_by'] = trim($this->reader->value);
                    break;

            }
        }
        $this->reader->close();

    }
}

<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use Carbon\Carbon;
use XMLReader;

class BrandXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'KREMMANIA.dbo.Brands';

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

        $brand = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($brand);
                    $brand = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;

            switch ($field) {
                case 'Id':
                    $this->reader->read();
                    $brand['id'] = (int) trim($this->reader->value);

                    break;

                case 'Link':
                    $this->reader->read();

                    $url = ltrim(trim($this->reader->value), '/');
                    $url = str_replace('http:', 'https:', $url);

                    $parsed = parse_url($url);
                    if (! ($parsed['scheme'] ?? null)) {
                        $url = 'https://' . $url;
                    }

                    $brand['url'] = $url;

                    break;

                case 'WhereToFind':
                    $this->reader->read();
                    $brand['where_to_find'] = trim($this->reader->value);

                    break;

                case 'IsApproved':
                    $this->reader->read();
                    $brand['approved'] = (int) trim($this->reader->value) === 1 ? now()->format('Y-m-d H:i:s') : null;

                    break;

                case 'PictureURL':
                    $this->reader->read();
                    $brand['image'] = trim($this->reader->value);

                    break;

                case 'CretOn':
                    $this->reader->read();

                    $date = trim($this->reader->value);

                    $format = 'Y-m-d\TH:i:s';
                    if (str_contains($date, '.')) {
                        $format = 'Y-m-d\TH:i:s.u';
                    }

                    $date = Carbon::createFromFormat($format, $date);
                    $brand['created_at'] = $date->format('Y-m-d H:i:s');

                    break;

                case 'ModOn':
                    $this->reader->read();

                    $date = trim($this->reader->value);

                    $format = 'Y-m-d\TH:i:s';
                    if (str_contains($date, '.')) {
                        $format = 'Y-m-d\TH:i:s.u';
                    }

                    $date = Carbon::createFromFormat($format, $date);
                    $brand['updated_at'] = $date->format('Y-m-d H:i:s');

                    break;

                case 'CretBy':
                    $this->reader->read();
                    $brand['created_by'] = trim($this->reader->value);

                    break;

                case 'ModBy':
                    $this->reader->read();
                    $brand['updated_by'] = trim($this->reader->value);

                    break;

                case 'Title':
                case 'Description':
                case 'Slug':
                    $this->reader->read();
                    $brand[strtolower($field)] = trim($this->reader->value) ?? null;

                    break;
            }
        }

        $this->reader->close();
    }
}

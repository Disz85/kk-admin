<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use Carbon\Carbon;
use XMLReader;

class BrandTagXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'KREMMANIA.dbo.BrandTags';

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
                case 'Id':
                    $this->reader->read();
                    $brandTag['id'] = trim($this->reader->value);

                    break;

                case 'Title':
                    $this->reader->read();
                    $brandTag['name'] = trim($this->reader->value) ?? null;

                    break;

                case 'Slug':
                    $this->reader->read();
                    $brandTag['slug'] = trim($this->reader->value) ?? null;

                    break;

                case 'Plural':
                    $this->reader->read();
                    $brandTag['description'] = trim($this->reader->value) ?? null;

                    break;

                case 'CretOn':
                    $this->reader->read();

                    $date = trim($this->reader->value);

                    $format = 'Y-m-d\TH:i:s';
                    if (str_contains($date, '.')) {
                        $format = 'Y-m-d\TH:i:s.u';
                    }

                    $date = Carbon::createFromFormat($format, $date);
                    $brandTag['created_at'] = $date->format('Y-m-d H:i:s');

                    break;

                case 'ModOn':
                    $this->reader->read();

                    $date = trim($this->reader->value);

                    $format = 'Y-m-d\TH:i:s';
                    if (str_contains($date, '.')) {
                        $format = 'Y-m-d\TH:i:s.u';
                    }

                    $date = Carbon::createFromFormat($format, $date);
                    $brandTag['updated_at'] = $date->format('Y-m-d H:i:s');

                    break;
            }
        }

        $this->reader->close();
    }
}

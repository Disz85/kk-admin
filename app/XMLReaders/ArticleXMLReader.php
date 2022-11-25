<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use Carbon\Carbon;
use XMLReader;

class ArticleXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'item';
    private const META_KEYS = [
        '_thumbnail_id',
        '_aioseop_description',
        '_aioseop_title',
    ];

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
        $article['authors'] = [];
        $article['tags'] = [];
        $article['categories'] = [];
        $article['embeds'] = [];

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($article);
                    $article = [];
                    $article['authors'] = [];
                    $article['tags'] = [];
                    $article['categories'] = [];
                    $article['embeds'] = [];
                } else {
                    continue;
                }
            }

            $field = $this->reader->name;

            switch ($field) {
                case 'wp:post_id':
                    $this->reader->read();
                    $article['id'] = (int) trim($this->reader->value);

                    break;

                case 'wp:post_date':
                    $this->reader->read();

                    $date = Carbon::createFromFormat('Y-m-d H:i:s', trim($this->reader->value), 'UTC');
                    $date->setTimezone('Europe/Budapest');
                    $article['created_at'] = $date->format('Y-m-d H:i:s');

                    break;

                case 'wp:status':
                    $this->reader->read();
                    $article['active'] = match (trim($this->reader->value)) {
                        'draft' => 0,
                        'publish' => 1,
                    };

                    break;

//                case 'wp:post_name':
                case 'title':
                    $this->reader->read();
                    $article[$field] = trim($this->reader->value);

                    break;

                case 'link':
                    $this->reader->read();
                    $article['slug'] = str_replace(
                        'https://magazin.kremmania.hu/',
                        '',
                        trim($this->reader->value)
                    );

                    break;

                case 'dc:creator':
                    $this->reader->read();
                    $article['authors'][] = trim($this->reader->value);

                    break;

                case 'content:encoded':
                    $this->reader->read();
                    $article['body'] = trim($this->reader->value);

                    break;

                case 'excerpt:encoded':
                    $this->reader->read();
                    $article['lead'] = trim($this->reader->value);

                    break;

                case 'guid':
                case 'description':
                    $this->reader->read();
                    $article[$field] = trim($this->reader->value);

                    break;

                case 'category':
                    $domain = $this->reader->getAttribute('domain');

                    if ($domain === 'post_tag') {
                        $article['tags'][] = trim($this->reader->getAttribute('nicename'));
                    }

                    if ($domain === 'category') {
                        $article['categories'][] = trim($this->reader->getAttribute('nicename'));
                    }

                    break;

                case 'wp:meta_key':
                    $this->reader->read();
                    $metaKey = trim($this->reader->value);

                    if (str_contains($metaKey, '_oembed_') && ! str_contains($metaKey, '_oembed_time_')) {
                        $this->reader->next();
                        $this->reader->next();
                        $this->reader->next();
                        $this->reader->read();

                        $metaValue = trim($this->reader->value);

                        if ($metaValue === '{{unknown}}') {
                            break;
                        }

                        $article['embeds'][] = $metaValue;

                        break;
                    }

                    if (in_array($metaKey, self::META_KEYS)) {
                        $this->reader->next();
                        $this->reader->next();
                        $this->reader->next();
                        $this->reader->read();

                        $metaValue = trim($this->reader->value);

                        if ($metaKey === '_thumbnail_id') {
                            $metaValue = (int) $metaValue;
                        }

                        $article[$metaKey] = $metaValue;

                        break;
                    }

                    break;
            }
        }

        $this->reader->close();
    }
}

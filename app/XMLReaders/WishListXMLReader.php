<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class WishListXMLReader extends AbstractXMLReader
{
    private const PARENT_NODE = 'KREMMANIA.dbo.Wishlist';

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

        $wishList = [];
        $depth = 1;
        while ($this->reader->read() && $depth != 0) {
             if($this->reader->nodeType === XMLReader::ELEMENT && $this->reader->name === self::PARENT_NODE){
                $lastNodeName = '';
                while($this->reader->read() && !($this->reader->nodeType === XMLReader::END_ELEMENT && $this->reader->name === self::PARENT_NODE)){
                    if($this->reader->nodeType == XMLReader::TEXT){
                        $wishList[$lastNodeName] = trim($this->reader->value);
                    }

                    $lastNodeName = $this->reader->name;
                }
             }

             if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if ($this->reader->name === self::PARENT_NODE) {
                    $callback($wishList);
                    $wishList = [];
                }
             }
        }
    }
}

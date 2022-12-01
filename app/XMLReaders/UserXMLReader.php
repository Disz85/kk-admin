<?php

namespace App\XMLReaders;

use App\XMLReaders\Abstracts\AbstractXMLReader;
use XMLReader;

class UserXMLReader
{
    protected XMLReader $reader;

    private const PARENT_NODES = ['KREMMANIA.dbo.Users', 'KREMMANIA.dbo.AspNetUsers', 'KREMMANIA.dbo.UserNickConnections'];

    /**
     * AbstractXMLReader constructor.
     */
    public function __construct()
    {
        $this->reader = new XMLReader();
    }

    /**
     * Returns the number of users in the XML document.
     *
     * @param string $path
     * @return int
     */
    public function count(string $path): int
    {
        $elements = 0;

        $this->reader->open($path);

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::ELEMENT && in_array($this->reader->name, self::PARENT_NODES)) {
                $elements++;
            }
        }

        $this->reader->close();

        return $elements;
    }

    public function getType(string $path){
        $this->reader->open($path);

        $depth = 1;
        while ($this->reader->read() && $depth != 0){
            if(in_array($this->reader->name, self::PARENT_NODES)){
                return str_replace('KREMMANIA.dbo.', '', $this->reader->name);
            }
        }
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
            if($this->reader->nodeType === XMLReader::ELEMENT && in_array($this->reader->name, self::PARENT_NODES)){
                $lastNodeName = '';
                while($this->reader->read() && !($this->reader->nodeType === XMLReader::END_ELEMENT && in_array($this->reader->name, self::PARENT_NODES))){
                    if($this->reader->nodeType == XMLReader::TEXT){
                        $wishList[$lastNodeName] = trim($this->reader->value);
                    }

                    $lastNodeName = $this->reader->name;
                }
            }

            if ($this->reader->nodeType === XMLReader::END_ELEMENT) {
                if (in_array($this->reader->name, self::PARENT_NODES)) {
                    $callback($wishList);
                    $wishList = [];
                }
            }
        }
    }
}

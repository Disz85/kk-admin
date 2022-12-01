<?php

namespace App\XMLReaders\Abstracts;

use XMLReader;

abstract class AbstractXMLReader
{
    protected XMLReader $reader;

    /**
     * AbstractXMLReader constructor.
     */
    public function __construct()
    {
        $this->reader = new XMLReader();
    }

    /**
     * Returns the number of authors in the XML document.
     *
     * @param string $path
     * @param string $elementName
     * @return int
     */
    protected function countElements(string $path, string $elementName): int
    {
        $elements = 0;

        $this->reader->open($path);

        while ($this->reader->read()) {
            if ($this->reader->nodeType === XMLReader::ELEMENT && $this->reader->name === $elementName) {
                $elements++;
            }
        }

        $this->reader->close();

        return $elements;
    }

    /**
     * Returns the number of elements in the XML document by calling countElements method.
     *
     * @param string $path
     * @return int
     */
    abstract public function count(string $path): int;

    /**
     * Reads the document.
     *
     * @param string $path
     * @param callable $callback
     */
    abstract public function read(string $path, callable $callback): void;

}

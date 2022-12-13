<?php

namespace App\XMLReaders;

use XMLReader;

class SchemaXMLReader
{
    public const TABLE_PREFIX = '_tmp_';

    // Get all columns from first node
    public function read($xmlFile)
    {
        $reader = new XMLReader();
        $reader->open($xmlFile);

        $columns = [];
        while ($reader->read()) {
            if ($reader->depth === 1) {
                $rowIdentifier = $reader->name;
            }

            if (
                $reader->nodeType === XMLReader::END_ELEMENT
                && $reader->depth === 2
            ) {
                $columns[$reader->name] = $reader->name;
            }
        }

        $reader->close();

        $tmp = explode('.', $rowIdentifier);
        $legacyTableName = end($tmp);
        $tableName = self::TABLE_PREFIX . $legacyTableName;

        return (object) [
            'table_name' => $tableName,
            'legacy_table_name' => $legacyTableName,
            'columns' => $columns,
            'row_identifier' => $rowIdentifier,
        ];
    }
}

<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use XMLReader;

class ImportXML extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:xml
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import XML file into a temporary table';

    // Get tablename from filename based on the pattern kremmania-TABLENAME-YYYY-MM-DD.xml
    private function getTableName($xmlFile)
    {
        preg_match('/(?<=kremmania-)(.*?)(?=-\d{4}-\d{2}-\d{2}.xml)/', basename($xmlFile), $match);

        return '_tmp_' . $match[0];
    }

    // Get all columns from first table node
    private function getColumns($xmlFile)
    {
        $reader = new XMLReader();
        $reader->open($xmlFile);

        $columns = [];
        $iteration = 0;
        while ($reader->read()) {
            if ($reader->depth === 1) {
                $iteration++;
                $rowIdentifier = $reader->name;
            }

            if (
                $reader->nodeType === XMLReader::END_ELEMENT
                && $reader->depth === 2
                && $iteration === 1
            ) {
                $columns[$rowIdentifier][] = $reader->name;
            }
        }

        $reader->close();

        return $columns;
    }

    private function getColumnNames($columns)
    {
        return array_values($columns)[0];
    }

    private function getColumnDefinitions($columns)
    {
        $columnDefinitions = array_map(function ($column) {
            return "$column LONGTEXT DEFAULT NULL";
        }, $this->getColumnNames($columns));

        return implode(",\n", $columnDefinitions);
    }

    private function createIndexQuery($path)
    {
        $columns = $this->getColumns($path);
        $tableName = $this->getTableName($path);

        $createIndexStatements = array_map(function ($column) use ($tableName) {
            return "CREATE INDEX idx_$column ON $tableName ($column);";
        }, $this->getColumnNames($columns));

        return implode("\n", $createIndexStatements);
    }

    /**
     * Import XML
     */
    public function handle()
    {
        $path = $this->option('path');

        if (! file_exists($path)) {
            return;
        }

        $tableName = $this->getTableName($path);
        $columns = $this->getColumns($path);
        $rowIdentifier = array_key_first($columns);
        $columnDefinitions = $this->getColumnDefinitions($columns);

        DB::unprepared("
            DROP TABLE IF EXISTS $tableName;

            CREATE TABLE $tableName ($columnDefinitions)
            ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            LOAD XML LOCAL INFILE '$path' INTO TABLE $tableName ROWS IDENTIFIED BY '<$rowIdentifier>';

            {$this->createIndexQuery($path)}
        ");
    }
}

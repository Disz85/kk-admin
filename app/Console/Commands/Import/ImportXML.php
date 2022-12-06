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
    private function getTableName($xmlFile) {
        preg_match('/(?<=kremmania-)(.*?)(?=-\d{4}-\d{2}-\d{2}.xml)/', basename($xmlFile), $match);
        return '_tmp_' . $match[0];
    }

    // Get all columns from first table node
    private function getColumns($xmlFile) {
        $reader = new XMLReader();
        $reader->open($xmlFile);

        $columns = [];
        $iteration = 0;
        while ($reader->read()) {

            if ($reader->depth === 1) {
                $iteration++;
            }

            if (
                $reader->nodeType === XMLReader::END_ELEMENT
                && $reader->depth === 2
                && $iteration === 1
            ) {
                $columns[] = $reader->name;
            }

        }

        $reader->close();

        return $columns;
    }

    // Create index query for all tables
    private function getCreateIndexQuery($path) {
        $tableName = $this->getTableName($path);

        $createIndexStatements = array_map(function ($column) use ($tableName) {
            return "CREATE INDEX idx_$column ON $tableName ($column);";
        }, $this->getColumns($path));

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

        $createIndexStatements = $this->getCreateIndexQuery($path);

        DB::unprepared("
            DROP TABLE IF EXISTS $tableName;

            CREATE TABLE $tableName
            ENGINE=CONNECT COLLATE='latin2_hungarian_ci' TABLE_TYPE=XML FILE_NAME='$path'
            TABNAME='data';

            ALTER TABLE $tableName ENGINE = InnoDB;

            $createIndexStatements
        ");

    }
}

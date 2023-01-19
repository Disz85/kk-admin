<?php

namespace App\Console\Commands\Import;

use App\XMLReaders\SchemaXMLReader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportXML extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:xml
                            {--path= : The path of the XML file}
                            {--columns=* : Select specified columns. By default use all columns.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import XML file into a temporary table';

    private function getColumnDefinitions(array $columns): string
    {
        $columnDefinitions = array_map(static function ($column) {
            return "$column LONGTEXT DEFAULT NULL";
        }, $columns);

        return implode(",\n", $columnDefinitions);
    }

    private function createIndexQuery(string $tableName, array $columns): string
    {
        $createIndexStatements = array_map(static function ($column) use ($tableName) {
            return "CREATE INDEX idx_$column ON $tableName ($column);";
        }, $columns);

        return implode("\n", $createIndexStatements);
    }

    /**
     * Import XML
     */
    public function handle(SchemaXMLReader $schemaReader): void
    {
        $path = $this->option('path');

        if (! file_exists($path)) {
            return;
        }

        $schema = $schemaReader->read($path);

        if ($columns = $this->option('columns')) {
            $columns = array_filter($schema->columns, fn (string $column) => in_array($column, $columns));
        } else {
            $columns = $schema->columns;
        }

        $columnDefinitions = $this->getColumnDefinitions($columns);

        $this->info("\nImport $schema->legacy_table_name as $schema->table_name.");

        $this->info(" Drop $schema->table_name table\n");
        DB::unprepared("DROP TABLE IF EXISTS $schema->table_name;");

        $this->info(" Create $schema->table_name table\n");
        DB::unprepared("
            CREATE TABLE $schema->table_name ($columnDefinitions)
            ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $this->info(" Load XML data into $schema->table_name table\n");
        DB::unprepared("
            LOAD XML LOCAL INFILE '$path' INTO TABLE $schema->table_name ROWS IDENTIFIED BY '<$schema->row_identifier>';
        ");

        $this->info(" Index $schema->table_name table\n");
        DB::unprepared($this->createIndexQuery($schema->table_name, $schema->columns));

        $this->info("Import is finished.");
    }
}

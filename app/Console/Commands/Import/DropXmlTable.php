<?php

namespace App\Console\Commands\Import;

use App\XMLReaders\SchemaXMLReader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropXmlTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cleanup
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop temporary table created from XML file';

    /**
     * Drop Termporary XML Table
     */
    public function handle(SchemaXMLReader $schemaReader): void
    {
        $path = $this->option('path');

        if (! file_exists($path)) {
            return;
        }

        $schema = $schemaReader->read($path);

        DB::unprepared("DROP TABLE IF EXISTS $schema->table_name;");

        $this->info("Table $schema->table_name dropped.");
    }

}

<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropXmlTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:drop-xml-table
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop temporary table created from XML file';

    // Get tablename from filename based on the pattern kremmania-TABLENAME-YYYY-MM-DD.xml
    private function getTableName($xmlFile)
    {
        preg_match('/(?<=kremmania-)(.*?)(?=-\d{4}-\d{2}-\d{2}.xml)/', basename($xmlFile), $match);

        return '_tmp_' . $match[0];
    }

    /**
     * Drop Termporary XML Table
     */
    public function handle()
    {
        $path = $this->option('path');

        if (! file_exists($path)) {
            return;
        }

        $tableName = $this->getTableName($path);

        DB::unprepared("DROP TABLE IF EXISTS $tableName;");
    }
}

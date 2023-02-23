<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCremeCategoriesConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:creme-categories-connection
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports creme categories connection from temp table file';

    public function handle(): void
    {
        $path = $this->option('path');
        $this->call(ImportXml::class, ['--path' => $path]);

        DB::statement("insert into categoryables (category_id, categoryable_id, categoryable_type)
select categories.id as categoryId, products.id as productId,'App\\\Models\\\Product' from _tmp_Cremes_CremeTags
inner join categories ON _tmp_Cremes_CremeTags.CremeTagsID = categories.legacy_id
inner join products ON _tmp_Cremes_CremeTags.CremeID = products.legacy_id
order by _tmp_Cremes_CremeTags.CremeID;");

        $this->call(DropXmlTable::class, [ '--path' => $path ]);
    }
}

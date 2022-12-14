<?php

namespace app\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users
                            {--path=* : The path(s) of the XML file(s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from XML files';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $paths = $this->option('path');

        foreach ($paths as $path) {
            $this->call(ImportXML::class, ['--path' => $path]);
        }

        $this->info("\nInsert _tmp_AspNetUsers into new app schema.");
        $this->importAspNetUsers();

        $this->info("\nInsert _tmp_Users into new app schema.");
        $this->importUsers();

        foreach ($paths as $path) {
            $this->call(DropXmlTable::class, ['--path' => $path]);
        }
    }

    private function importUsers()
    {
        DB::unprepared("
            INSERT INTO users (
                username,
                legacy_nickname,
                slug,
                birth_year,
                skin_type,
                skin_concern
            )
            SELECT
                TRIM(_tmp_Users.NickName),
                TRIM(_tmp_Users.NickName),
                TRIM(_tmp_Users.Slug),
                TRIM(_tmp_Users.BirthYear),
                LOWER(TRIM(_tmp_Users.SkinTypeText)),
                LOWER(TRIM(_tmp_Users.SkinConcernText))
            FROM _tmp_Users
            ON DUPLICATE KEY UPDATE
                legacy_nickname = VALUES(legacy_nickname),
                slug = VALUES(slug),
                birth_year = VALUES(birth_year),
                skin_type = VALUES(skin_type),
                skin_concern = VALUES(skin_concern);
        ");
    }

    private function importAspNetUsers()
    {
        DB::unprepared("
            INSERT INTO users (
                legacy_id,
                legacy_username,
                username,
                email,
                created_at
            )
            WITH _grouped_aspnet_users AS (
                SELECT
                    _tmp_AspNetUsers.*,
                    ROW_NUMBER() OVER (PARTITION BY TRIM(Email) ORDER BY CreateDate DESC) AS rn
                FROM _tmp_AspNetUsers
            )
            SELECT
                TRIM(Id),
                TRIM(UserName),
                TRIM(UserName),
                TRIM(Email),
                TRIM(CreateDate)
            FROM _grouped_aspnet_users WHERE rn = 1;
        ");
    }
}

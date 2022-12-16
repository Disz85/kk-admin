<?php

namespace App\Console\Commands\Authorization;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Console\Command;

class CreateRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:create-roles-and-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the roles and the permissions';

    /**
     * Execute the console command.
     *
     * @param RolesAndPermissionsSeeder $seeder
     * @return void
     */
    public function handle(RolesAndPermissionsSeeder $seeder): void
    {
        $seeder->run();
    }
}

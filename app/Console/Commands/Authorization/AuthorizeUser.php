<?php

namespace App\Console\Commands\Authorization;

use App\Models\User;
use Illuminate\Console\Command;

class AuthorizeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:authorize-user
                           {--id= : User ID}
                           {roles* : A list of roles separated by space}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Authorizes user with roles';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $user = User::findOrFail($this->option('id'));

        foreach ($this->argument('roles') as $role) {
            $user->assignRole($role);
        }
    }
}

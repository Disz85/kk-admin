<?php

namespace App\Console\Commands\Authorization;

use App\Models\User;
use Illuminate\Console\Command;

class UnauthorizeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:unauthorize-user
                           {--id= : User ID}
                           {roles* : List of roles separated by space}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revokes roles from user.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $user = User::findOrFail($this->option('id'));

        foreach ($this->argument('roles') as $role) {
            if (! $user->hasRole($role)) {
                continue;
            }

            $user->removeRole($role);
        }
    }
}

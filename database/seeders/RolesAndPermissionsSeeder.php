<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $defaultPermission = Permission::firstOrCreate([
            'name' => config('auth.default_permission'),
            'guard_name' => config('auth.role_guard'),
        ]);

        foreach (config('auth.roles') as $roleData) {
            $role = Role::firstOrCreate([
                'name' => $roleData['name'],
                'guard_name' => config('auth.role_guard'),
            ]);

            if (! array_key_exists('permissions', $roleData)) {
                continue;
            }

            foreach ($roleData['permissions'] as $permissionData) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionData,
                    'guard_name' => config('auth.role_guard'),
                ]);

                if (! $role->hasPermissionTo($permission)) {
                    $permission->assignRole($role);
                }
            }

            if ($role->name !== 'super-admin' && ! $role->hasPermissionTo($defaultPermission)) {
                $defaultPermission->assignRole($role);
            }
        }
    }
}

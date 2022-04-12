<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::create(['name' => 'Super-Admin']);
        $user = \App\Models\User::find(1);
        $user->assignRole($role);

        $role = Role::create(['name' => 'Admin']);
        $user = \App\Models\User::find(2);
        $user->assignRole($role);
    }
}

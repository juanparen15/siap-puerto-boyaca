<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'operador', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'interventoria', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);

        $editUserTaskRole = Permission::create(['name' => 'edit user tasks']);
        $deleteUserTaskRole = Permission::create(['name' => 'delete user tasks']);

        $adminRole->givePermissionTo($editUserTaskRole, $deleteUserTaskRole);


    }
}

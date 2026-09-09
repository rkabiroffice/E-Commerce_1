<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $role = Role::where('name', 'admin')->where('guard_name', 'web')->firstOrFail();
        $role->syncPermissions(Permission::where('guard_name', 'web')->get());
    }
}

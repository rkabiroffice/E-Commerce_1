<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ModelHasRolesTableSeeder extends Seeder
{
    public function run()
    {
        $adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
        $admin = User::where('email', $adminEmail)->firstOrFail();
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'web')->firstOrFail();

        $admin->syncRoles([$adminRole]);
    }
}

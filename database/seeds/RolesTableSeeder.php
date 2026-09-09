<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
    }
}

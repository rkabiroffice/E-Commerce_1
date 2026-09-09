<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
        $adminName = getenv('ADMIN_NAME') ?: 'Administrator';
        $adminPassword = getenv('ADMIN_PASSWORD') ?: 'password';

        $admin = User::firstOrNew(['email' => $adminEmail]);
        $admin->name = $adminName;
        $admin->user_type = 'admin';
        $admin->password = Hash::make($adminPassword);
        $admin->email_verified_at = now();
        $admin->save();
    }
}

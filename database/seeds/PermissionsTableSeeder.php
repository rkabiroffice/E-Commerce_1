<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionNames = [];
        foreach (File::allFiles(app_path('Http/Controllers')) as $file) {
            preg_match_all('/permission:([^\'\]]+)/', $file->getContents(), $matches);

            foreach ($matches[1] as $permissionList) {
                foreach (explode('|', $permissionList) as $permissionName) {
                    $permissionName = trim($permissionName, " '\"");

                    if ($permissionName !== '') {
                        $permissionNames[$permissionName] = true;
                    }
                }
            }
        }

        foreach (array_keys($permissionNames) as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['section' => 'admin']
            );
        }
    }
}

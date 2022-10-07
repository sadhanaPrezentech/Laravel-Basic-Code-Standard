<?php

namespace Database\Seeders;

use App\Helpers\FunctionHelper;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $web_modules = config('modules.list.web', []);
        $api_modules = config('modules.list.api', []);
        $permissions = config('modules.permission', []);

        // WEB permission
        foreach ($web_modules as $module => $data) {
            foreach ($permissions as $permission => $label) {
                $mod_perm = Str::setModulePermission($permission, $module);
                if (FunctionHelper::permissionAllowed($data, $permission)) {
                    if ($mod_perm) {
                        Permission::findOrCreate($mod_perm, 'web');
                    }
                } else {
                    Permission::where('name', $mod_perm)->delete();
                }
            }
        }

        // API permission
        foreach ($api_modules as $module => $text) {
            foreach ($permissions as $permission => $label) {
                $mod_perm = Str::setModulePermission($permission, $module);
                if (FunctionHelper::permissionAllowed($data, $permission)) {
                    if ($mod_perm) {
                        Permission::findOrCreate($mod_perm, 'api');
                    }
                } else {
                    Permission::where('name', $mod_perm)->delete();
                }
            }
        }
    }
}

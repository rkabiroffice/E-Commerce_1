<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $with = ['role_translations'];

  public function givePermissionTo(array|int|string|Permission $permissions)
  {
    foreach ($this->permissionIds($permissions) as $permissionId) {
      DB::table('role_has_permissions')->insertOrIgnore([
        'permission_id' => $permissionId,
        'role_id' => $this->id,
      ]);
    }

    return $this;
  }

  public function syncPermissions(array|int|string|Permission $permissions)
  {
    DB::table('role_has_permissions')->where('role_id', $this->id)->delete();

    return $this->givePermissionTo($permissions);
  }

  private function permissionIds(array|int|string|Permission $permissions): array
  {
    $permissions = is_array($permissions) ? $permissions : [$permissions];
    $names = [];
    $ids = [];
    foreach ($permissions as $permission) {
      if (is_numeric($permission)) {
        $ids[] = (int) $permission;
      } elseif (is_string($permission)) {
        $names[] = $permission;
      } elseif ($permission instanceof Permission) {
        $ids[] = $permission->id;
      }
    }
    if ($names) {
      $ids = array_merge($ids, Permission::whereIn('name', $names)->pluck('id')->all());
    }

    return array_values(array_unique($ids));
  }

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $role_translation = $this->role_translations->where('lang', $lang)->first();
        return $role_translation != null ? $role_translation->$field : $this->$field;
    }

    public function role_translations(){
      return $this->hasMany(RoleTranslation::class);
    }
}

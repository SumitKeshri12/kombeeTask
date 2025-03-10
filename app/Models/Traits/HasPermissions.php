<?php

namespace App\Models\Traits;

use App\Models\Permission;

trait HasPermissions
{
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('slug', $permission);
        }
        return !!$permission->intersect($this->permissions)->count();
    }

    public function hasPermissionTo($permission)
    {
        return $this->hasPermission($permission);
    }

    public function hasAnyPermission(...$permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(...$permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
} 
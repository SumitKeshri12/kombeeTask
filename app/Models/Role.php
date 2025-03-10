<?php

namespace App\Models;

use App\Models\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasPermissions;

    protected $fillable = ['name', 'slug'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    // Helper method to check if role has specific permission
    public function hasPermission($permission)
    {
        return $this->permissions->contains('slug', $permission);
    }
} 
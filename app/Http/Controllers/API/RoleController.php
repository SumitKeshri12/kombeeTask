<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    const PERMISSIONS_VALIDATION_RULE = 'required|array|exists:permissions,id';

    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return RoleResource::collection($roles);
    }

    public function getUserRoles()
    {
        $user = auth()->user();
        return RoleResource::collection($user->roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => self::PERMISSIONS_VALIDATION_RULE
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return new RoleResource($role->load('permissions'));
    }

    public function show(Role $role)
    {
        return new RoleResource($role->load('permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => self::PERMISSIONS_VALIDATION_RULE
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return new RoleResource($role->load('permissions'));
    }

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return response()->json([
                'message' => 'Cannot delete role with associated users'
            ], 422);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function attachPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => self::PERMISSIONS_VALIDATION_RULE
        ]);

        $role->permissions()->attach($request->permissions);
        return response()->json($role->load('permissions'));
    }

    public function detachPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => self::PERMISSIONS_VALIDATION_RULE
        ]);

        $role->permissions()->detach($request->permissions);
        return response()->json($role->load('permissions'));
    }
}
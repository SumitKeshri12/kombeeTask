<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(10);
        return PermissionResource::collection($permissions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        $permission = Permission::create($validated);
        return new PermissionResource($permission);
    }

    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($validated);
        return new PermissionResource($permission);
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->exists()) {
            return response()->json([
                'message' => 'Cannot delete permission that is assigned to roles'
            ], 422);
        }

        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }

    public function syncRoles(Request $request, Permission $permission)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        try {
            $permission->roles()->sync($request->roles);
            return response()->json(['message' => 'Roles synced successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error syncing roles'], 500);
        }
    }

    public function getRoles(Permission $permission)
    {
        return response()->json($permission->roles);
    }
} 
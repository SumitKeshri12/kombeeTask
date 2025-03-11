<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Events\UserCreated;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    const USER_VALIDATION_RULE = 'required|string|max:255';

    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => self::USER_VALIDATION_RULE,
            'last_name' => self::USER_VALIDATION_RULE,
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => 'required|array|exists:roles,id'
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $user->roles()->sync($validated['roles']);

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user->load('roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => self::USER_VALIDATION_RULE,
            'last_name' => self::USER_VALIDATION_RULE,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => 'required|array|exists:roles,id'
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email']
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->roles()->sync($validated['roles']);

        return new UserResource($user->load('roles'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete your own account'
            ], 403);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function export($format)
    {
        return match($format) {
            'csv' => Excel::download(new UsersExport, 'users.csv'),
            'excel' => Excel::download(new UsersExport, 'users.xlsx'),
            'pdf' => Excel::download(new UsersExport, 'users.pdf'),
            default => response()->json(['error' => 'Invalid format'], 400)
        };
    }
} 
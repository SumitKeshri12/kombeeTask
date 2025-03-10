<?php

namespace App\Http\Controllers\Web;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersPdfExport;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())  // Exclude logged-in user
            ->with('roles')
            ->paginate(10);
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $cities = City::orderBy('name')->get();
        $genderOptions = User::getGenderOptions();
        $hobbyOptions = User::getHobbyOptions();
        return view('users.create', compact('roles', 'cities', 'genderOptions', 'hobbyOptions'));
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'user' => $user
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'User created successfully');

        } catch (Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating user: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Please use the profile section to edit your own account.');
        }

        $roles = Role::all();
        $cities = City::orderBy('name')->get();
        $genderOptions = User::getGenderOptions();
        $hobbyOptions = User::getHobbyOptions();
        
        return view('users.edit', compact('user', 'roles', 'cities', 'genderOptions', 'hobbyOptions'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            // Only update password if provided
            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user' => $user
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');

        } catch (Exception $e) {
            \Log::error('User update error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating user: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportPdf()
    {
        $exporter = new UsersPdfExport();
        return $exporter->download();
    }
} 
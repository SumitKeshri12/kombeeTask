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
use App\Traits\GeneratesIdempotencyKey;

class UserController extends Controller
{
    use GeneratesIdempotencyKey;

    const USER_VALIDATION_RULE = 'required|string|max:255';

    public function __construct()
    {
        $this->middleware('idempotent')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $users = User::with('roles')->paginate(10);
        // $users = [];
        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => self::USER_VALIDATION_RULE,
            'last_name' => self::USER_VALIDATION_RULE,
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'contact_number' => 'required|string',
            'postcode' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'hobbies' => 'required|array',
            'city_id' => 'required|exists:cities,id'
        ]);

        $user = User::create($validated);
        
        // Assign default role
        $user->assignRole('User');

        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user->load('roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'contact_number' => 'sometimes|string',
            'postcode' => 'sometimes|string',
            'gender' => 'sometimes|in:male,female,other',
            'hobbies' => 'sometimes|array',
            'city_id' => 'sometimes|exists:cities,id'
        ]);

        $user->update($validated);

        return new UserResource($user);
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

    /**
     * Example of generating an idempotency key for clients
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdempotencyKey(Request $request)
    {
        return response()->json([
            'idempotency_key' => $this->generateIdempotencyKey()
        ]);
    }
} 
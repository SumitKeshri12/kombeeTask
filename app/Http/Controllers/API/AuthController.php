<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($validated)) {
                $user = Auth::user();
                $token = $user->createToken('api-token')->accessToken;

                $user->authorization = $token;

                return $this->successResponse([
                    $user
                ], 'Login successful');
            }

            return $this->errorResponse('Invalid credentials', 401);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        }
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'contact_number' => 'required|string',
                'postcode' => 'required|string',
                'gender' => 'required|in:male,female,other',
                'hobbies' => 'required|array',
                'city_id' => 'required|exists:cities,id'
            ]);

            $user = User::create($validated);
            $user->assignRole('User');

            $token = $user->createToken('api-token')->accessToken;

            return $this->successResponse([
                'authorization' => $token,
                'user' => $user
            ], 'Registration successful', 201);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->successResponse(null, 'Successfully logged out');
    }

    public function getToken(Request $request)
    {
        $token = $request->user()->createToken('api-token')->accessToken;
        return $this->successResponse(['token' => $token]);
    }
}

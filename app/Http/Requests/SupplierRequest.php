<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $action = $this->route()->getActionMethod();
        $user = $this->user();
        
        Log::info('SupplierRequest authorization check', [
            'action' => $action,
            'user_id' => $user ? $user->id : null,
            'user_roles' => $user ? $user->roles->pluck('name') : [],
            'user_permissions' => $user ? $user->permissions->pluck('name') : []
        ]);

        $result = match($action) {
            'store' => $user && $user->can('create-suppliers'),
            'update' => $user && $user->can('edit-suppliers'),
            'destroy' => $user && $user->can('delete-suppliers'),
            default => true
        };

        Log::info('Authorization result', ['result' => $result]);
        return $result;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
        ];
    }
}

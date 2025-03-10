<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:suppliers,email,' . $this->supplier->id,
            'phone' => 'required|string|max:20',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'phone.required' => 'The phone field is required.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
            'city_id.required' => 'Please select a city.',
            'city_id.exists' => 'The selected city is invalid.',
            'address.required' => 'The address field is required.',
            'address.max' => 'The address may not be greater than 500 characters.',
        ];
    }
} 
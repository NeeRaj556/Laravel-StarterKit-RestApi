<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\BaseRequestValidation;
use Illuminate\Foundation\Http\FormRequest;

class RegisterValidation extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'profile_picture' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,gif', 'max:255'],
            'role' => ['nullable', 'string', 'in:doctor,admin,user'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'longitude' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'string', 'max:255'],
        ];
    }
}

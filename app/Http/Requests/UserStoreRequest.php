<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['owner', 'admin']);
    }

    public function rules(): array
    {
        $actor = Auth::user();
        $roleRule = Rule::in(['owner', 'admin', 'user']);
        if ($actor && $actor->role === 'admin') {
            $roleRule = Rule::in(['admin', 'user']);
        }

        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', $roleRule],
            'is_approved' => ['sometimes', 'boolean'],
        ];
    }
}


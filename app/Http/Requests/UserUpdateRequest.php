<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $actor = Auth::user();
        $target = $this->route('user');
        if (!$actor || !$target instanceof User) {
            return false;
        }
        if ($target->role === 'owner' && $actor->role !== 'owner') {
            return false;
        }
        return in_array($actor->role, ['owner', 'admin']);
    }

    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');
        $actor = Auth::user();

        $roleRule = Rule::in(['owner', 'admin', 'user']);
        if ($actor && $actor->role === 'admin') {
            $roleRule = Rule::in(['admin', 'user']);
        }

        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', $roleRule],
            'is_approved' => ['sometimes', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            /** @var User $target */
            $target = $this->route('user');
            $actor = Auth::user();
            if (!$actor || !$target) return;

            // Prevent admin from editing owner
            if ($target->role === 'owner' && $actor->role !== 'owner') {
                $validator->errors()->add('role', 'Admin tidak boleh mengubah user owner.');
            }

            // Prevent owner from locking themselves out
            if ($actor->id === $target->id) {
                $newRole = $this->input('role');
                if ($newRole && !in_array($newRole, ['owner', 'admin'])) {
                    $validator->errors()->add('role', 'Anda tidak boleh menurunkan role Anda sendiri sehingga kehilangan akses.');
                }
            }
        }];
    }
}

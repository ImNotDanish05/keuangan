<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return in_array($actor->role, ['owner', 'admin']);
    }

    public function view(User $actor, User $target): bool
    {
        return in_array($actor->role, ['owner', 'admin']);
    }

    public function create(User $actor): bool
    {
        return in_array($actor->role, ['owner', 'admin']);
    }

    public function update(User $actor, User $target): bool
    {
        if ($target->role === 'owner' && $actor->role !== 'owner') {
            return false;
        }
        return in_array($actor->role, ['owner', 'admin']);
    }

    public function delete(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return false; // cannot delete self
        }
        if ($target->role === 'owner' && $actor->role !== 'owner') {
            return false;
        }
        return in_array($actor->role, ['owner', 'admin']);
    }

    public function promoteToOwner(User $actor): bool
    {
        return $actor->role === 'owner';
    }
}


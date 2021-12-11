<?php

namespace App\Policies;

use App\Models\Bell;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BellPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Bell $bell): bool
    {
        return $bell->user_id === $user->id;
    }
}

<?php

namespace App\Contracts\Actions;

use App\Models\User;

interface UpdatesUser
{
    public function __invoke(User $user, array $data): User;
}

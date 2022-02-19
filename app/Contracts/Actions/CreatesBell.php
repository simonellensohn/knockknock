<?php

namespace App\Contracts\Actions;

use App\Models\Bell;
use App\Models\User;

interface CreatesBell
{
    public function __invoke(User $user, array $data): Bell;
}

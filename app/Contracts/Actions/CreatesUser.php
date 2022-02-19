<?php

namespace App\Contracts\Actions;

use App\Models\User;

interface CreatesUser
{
    public function __invoke(array $data): User;
}

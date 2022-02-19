<?php

namespace App\Contracts\Actions;

use App\Models\Bell;

interface UpdatesBell
{
    public function __invoke(Bell $bell, array $data): Bell;
}

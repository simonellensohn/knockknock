<?php

namespace App\Actions;

use App\Models\Bell;
use App\Models\User;
use App\Contracts\Actions\CreatesBell;
use Illuminate\Support\Facades\Validator;

class CreateBell implements CreatesBell
{
    public function __invoke(User $user, array $data): Bell
    {
        $data = Validator::validate($data, [
            'name' => ['required', 'string', 'max:50', 'unique:bells'],
            'threshold' => [
                'required',
                'numeric',
                'between:1,100',
                'unique:bells',
            ],
        ]);

        return $user->bells()->create($data);
    }
}

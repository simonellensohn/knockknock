<?php

namespace App\Actions;

use App\Contracts\Actions\CreatesBell;
use App\Models\Bell;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateBell implements CreatesBell
{
    public function __invoke(User $user, array $data): Bell
    {
        $data = Validator::validate($data, [
            'name' => ['required', 'string', 'max:50', 'unique:bells'],
            'min_volume' => [
                'required',
                'numeric',
                'between:1,100',
                'lt:max_volume',
            ],
            'max_volume' => [
                'required',
                'numeric',
                'between:1,100',
                'gt:min_volume',
            ],
        ]);

        return $user->bells()->create($data);
    }
}

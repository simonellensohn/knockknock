<?php

namespace App\Actions;

use App\Contracts\Actions\CreatesUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateUser implements CreatesUser
{
    public function __invoke(array $data): User
    {
        $data = Validator::validate($data, [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['required'],
        ]);

        return User::create($data);
    }
}

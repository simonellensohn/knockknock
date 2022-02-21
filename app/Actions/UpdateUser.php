<?php

namespace App\Actions;

use App\Contracts\Actions\UpdatesUser;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateUser implements UpdatesUser
{
    public function __invoke(User $user, array $data): User
    {
        $data = Validator::validate($data, [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignoreModel($user)],
            'password' => ['nullable'],
        ]);

        $user->fill(Arr::except($data, 'password'));

        if (isset($data['password']) && ! blank($data['password'])) {
            $user->password = $data['password'];
        }

        return tap($user)->save();
    }
}

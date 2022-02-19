<?php

namespace App\Actions;

use App\Models\Bell;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Contracts\Actions\CreatesBell;
use App\Contracts\Actions\UpdatesBell;
use Illuminate\Support\Facades\Validator;

class UpdateBell implements UpdatesBell
{
    public function __invoke(Bell $bell, array $data): Bell
    {
        $data = Validator::validate($data, [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bells')->ignoreModel($bell),
            ],
            'threshold' => [
                'required',
                'numeric',
                'between:1,100',
                Rule::unique('bells')->ignoreModel($bell),
            ],
            'active' => [
                'required',
                'boolean',
            ],
        ]);

        return tap($bell)->update($data);
    }
}

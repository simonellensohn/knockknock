<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RingBellRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->bell);
    }

    public function rules(): array
    {
        return [
            'volume' => 'required|numeric',
            'events' => 'required|array',
        ];
    }
}

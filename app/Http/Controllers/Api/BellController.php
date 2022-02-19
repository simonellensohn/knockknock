<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BellCollection;
use App\Models\Bell;

class BellController extends Controller
{
    public function __invoke(): BellCollection
    {
        return new BellCollection(Bell::all());
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BellResource;
use App\Models\Bell;

class BellController extends Controller
{
    public function __invoke()
    {
        $bells = Bell::all();

        return BellResource::collection($bells);
    }
}

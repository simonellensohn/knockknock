<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BellCollection;
use App\Http\Resources\BellResource;
use App\Models\Bell;

class BellController extends Controller
{
    public function index(): BellCollection
    {
        return new BellCollection(Bell::all());
    }

    public function show(Bell $bell): BellResource
    {
        return new BellResource($bell);
    }
}

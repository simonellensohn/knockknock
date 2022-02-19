<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BellResource;
use App\Models\Bell;
use App\Http\Resources\BellCollection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BellController extends Controller
{
    public function __invoke(): BellCollection
    {
        return new BellCollection(Bell::all());
    }
}

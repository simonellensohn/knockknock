<?php

namespace App\Http\Controllers;

use App\Models\Bell;
use Illuminate\Http\Response;

class RingBellController extends Controller
{
    public function __invoke(Bell $bell): Response
    {
        $this->authorize('update', $bell);

        $bell->ring();

        return response()->noContent();
    }
}

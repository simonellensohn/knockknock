<?php

namespace App\Http\Controllers;

use App\Http\Resources\RingResource;
use App\Models\Ring;
use Inertia\Inertia;
use Inertia\Response;

class RingsController extends Controller
{
    public function index(): Response
    {
        $rings = Ring::with('bell')->latest()->paginate(15);

        return Inertia::render('Rings/Index', [
            'rings' => RingResource::collection($rings),
        ]);
    }
}

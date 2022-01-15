<?php

namespace App\Http\Controllers;

use App\Http\Resources\RingResource;
use App\Models\Ring;
use Inertia\Inertia;

class RingsController extends Controller
{
    public function index()
    {
        $rings = Ring::with('bell')->latest()->paginate(15);

        return Inertia::render('Rings/Index', [
            'rings' => RingResource::collection($rings),
        ]);
    }
}

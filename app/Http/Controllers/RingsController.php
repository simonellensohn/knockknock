<?php

namespace App\Http\Controllers;

use App\Models\Ring;
use App\Http\Resources\BellResource;
use App\Models\Bell;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RingResource;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
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

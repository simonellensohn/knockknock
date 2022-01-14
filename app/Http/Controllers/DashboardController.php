<?php

namespace App\Http\Controllers;

use App\Models\Ring;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $latestRing = Ring::latest()->first();

        return Inertia::render('Dashboard/Index', [
            'rings' => Ring::all(),
            'totalRings' => Ring::count(),
            'lastRing' => [
                'readable' => $latestRing->created_at->diffForHumans(),
                'date' => $latestRing->created_at->format('d.m.Y H:i:s')
            ],
            'averageVolume' => round(Ring::avg('volume'), 2),
        ]);
    }
}

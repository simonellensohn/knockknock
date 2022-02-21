<?php

namespace App\Http\Controllers;

use App\Models\Ring;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $lastRing = Ring::latest()->firstOrNew();

        return Inertia::render('Dashboard/Index', [
            'averageVolume' => round((float) Ring::avg('volume'), 2),
            'lastRing' => [
                'date' => $lastRing->created_at?->format('d.m.Y H:i:s'),
                'readable' => $lastRing->created_at?->diffForHumans(),
            ],
            'totalRings' => Ring::count(),
        ]);
    }
}

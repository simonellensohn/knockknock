<?php

namespace App\Http\Controllers;

use App\Models\Ring;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Index', ['rings' => Ring::all()]);
    }
}

<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Actions\CreateBell;
use App\Actions\UpdateBell;
use Illuminate\Http\Request;
use App\Http\Resources\BellResource;
use App\Models\Bell;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BellsController extends Controller
{
    public function index(): Response
    {
        $bells = Bell::query()->withCount('rings')->get();

        return Inertia::render('Bells/Index', [
            'bells' => BellResource::collection($bells),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Bells/Create');
    }

    public function store(Request $request, CreateBell $createBell): RedirectResponse
    {
        $bell = $createBell($request->user(), $request->all());

        return Redirect::route('bells.index')
            ->with('success', "Bell {$bell->name} created.");
    }

    public function edit(Bell $bell): Response
    {
        return Inertia::render('Bells/Edit', [
            'bell' => new BellResource($bell),
        ]);
    }

    public function update(Request $request, Bell $bell, UpdateBell $updateBell): RedirectResponse
    {
        $bell = $updateBell($bell, $request->all());

        return Redirect::back()
            ->with('success', "Bell {$bell->name} updated.");
    }

    public function destroy(Bell $bell): RedirectResponse
    {
        $bell->delete();

        return Redirect::route('bells.index')
            ->with('success', "Bell {$bell->name} deleted.");
    }
}

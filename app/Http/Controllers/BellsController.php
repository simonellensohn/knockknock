<?php

namespace App\Http\Controllers;

use App\Http\Resources\BellResource;
use App\Models\Bell;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BellsController extends Controller
{
    public function index()
    {
        $bells = Bell::query()->withCount('rings')->get();

        return Inertia::render('Bells/Index', [
            'bells' => BellResource::collection($bells),
        ]);
    }

    public function create()
    {
        return Inertia::render('Bells/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50', 'unique:bells'],
            'threshold' => [
                'required',
                'numeric',
                'between:1,100',
                'unique:bells',
            ],
        ]);

        Auth::user()->bells()->create(Request::only('name', 'threshold'));

        return Redirect::route('bells.index')->with('success', 'Bell created.');
    }

    public function edit(Bell $bell)
    {
        return Inertia::render('Bells/Edit', [
            'bell' => new BellResource($bell),
        ]);
    }

    public function update(Bell $bell)
    {
        Request::validate([
            'name' => [
                'required',
                'max:50',
                Rule::unique('bells')->ignoreModel($bell),
            ],
            'threshold' => [
                'required',
                'numeric',
                'between:1,100',
                Rule::unique('bells')->ignoreModel($bell),
            ],
            'active' => [
                'required',
                'boolean',
            ],
        ]);

        $bell->update(Request::only('name', 'threshold', 'active'));

        return Redirect::back()->with('success', 'Bell updated.');
    }

    public function destroy(Bell $bell)
    {
        $bell->delete();

        return Redirect::route('bells.index')->with('success', 'Bell deleted.');
    }
}

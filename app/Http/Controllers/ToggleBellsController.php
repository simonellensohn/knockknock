<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ToggleBellsController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->user()->bells()->update([
            'active' => $active = $request->boolean('active'),
        ]);

        return Redirect::back()->with('success', 'Bells '.($active ? 'activated' : 'deactivated').'.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Sanctum\PersonalAccessToken;

class UserAccessTokensController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->user()->createToken($request->input('name', 'default'));

        return Redirect::back()->with('success', 'Token created.');
    }

    public function destroy(Request $request, PersonalAccessToken $token): RedirectResponse
    {
        abort_if($token->tokenable->isNot($request->user()), 404);

        $token->delete();

        return Redirect::back()->with('success', 'Token deleted.');
    }
}

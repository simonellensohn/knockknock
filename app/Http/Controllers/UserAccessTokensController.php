<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Sanctum\PersonalAccessToken;

class UserAccessTokensController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 404);

        $user->createToken($request->input('name', 'default'));

        return Redirect::back()->with('success', 'Token created.');
    }

    public function destroy(User $user, PersonalAccessToken $token): RedirectResponse
    {
        abort_unless($token->tokenable->is($user), 404);

        $token->delete();

        return Redirect::back()->with('success', 'Token deleted.');
    }
}

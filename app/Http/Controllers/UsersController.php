<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\CreatesUser;
use App\Contracts\Actions\UpdatesUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Users/Index', [
            'users' => UserResource::collection(User::all()),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request, CreatesUser $createUser): RedirectResponse
    {
        $user = $createUser($request->all());

        return Redirect::route('users.index')->with('success', "User {$user->name} created.");
    }

    public function edit(Request $request, User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => new UserResource($user),
            'accessTokens' => $user->is($request->user()) ? $user->tokens : [],
        ]);
    }

    public function update(Request $request, User $user, UpdatesUser $updateUser): RedirectResponse
    {
        $user = $updateUser($user, $request->all());

        return Redirect::back()->with('success', "User {$user->name} updated.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return Redirect::route('users.index')->with('success', 'User deleted.');
    }
}

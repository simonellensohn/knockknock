<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        return Redirect::route('users.index')->with('success', 'User created.');
    }

    public function edit(Request $request, User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => new UserResource($user),
            'accessTokens' => $user->is($request->user()) ? $user->tokens : [],
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
        ]);

        $user->update($request->only('first_name', 'last_name', 'email'));

        if ($password = $request->input('password')) {
            $user->update(['password' => $password]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }
}

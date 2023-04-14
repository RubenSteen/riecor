<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BlockController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        // Cannot unblock yourself
        if (Auth::user()->id === $user->id) {
            return Redirect::back()->with('error', 'You cannot block yourself');
        }

        // Cannot block someone that you are already blocking
        if (Auth::user()->blocks->contains('id', $user->id)) {
            return Redirect::back()->with('error', "Already blocking $user->name");
        }

        Auth::user()->blocks()->attach($user);

        return Redirect::back()->with('success', "Blocked $user->name");
    }

    public function delete(User $user): RedirectResponse
    {
        // Cannot unblock yourself
        if (Auth::user()->id === $user->id) {
            return Redirect::back()->with('error', 'You cannot unblock yourself');
        }

        // Cannot unblock that the user doesn't follow
        if (Auth::user()->blocks->doesntContain('id', $user->id)) {
            return Redirect::back()->with('error', "You are not blocking $user->name");
        }

        Auth::user()->blocks()->detach($user);

        return Redirect::back()->with('success', "Unblocked $user->name");
    }
}

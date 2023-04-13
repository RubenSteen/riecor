<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        // Cannot follow yourself
        if (Auth::user()->id === $user->id) {
            return Redirect::back()->with('error', 'You cannot follow yourself');
        }

        // Cannot follow someone that you are already following
        if (Auth::user()->follows->contains('id', $user->id)) {
            return Redirect::back()->with('error', "Already following $user->name");
        }

        Auth::user()->follows()->attach($user);

        return Redirect::back()->with('success', "Following $user->name");
    }

    public function delete(User $user): RedirectResponse
    {
        // Cannot unfollow yourself
        if (Auth::user()->id === $user->id) {
            return Redirect::back()->with('error', 'You cannot unfollow yourself');
        }

        // Cannot unfollow that the user doesn't follow
        if (Auth::user()->follows->doesntContain('id', $user->id)) {
            return Redirect::back()->with('error', "You are not following $user->name");
        }

        Auth::user()->follows()->detach($user);

        return Redirect::back()->with('success', "Unfollowing $user->name");
    }
}

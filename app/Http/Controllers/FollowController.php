<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id) {
            return Redirect::back()->with('error', 'You cannot follow yourself');
        }

        Auth::user()->follows()->attach($user);

        return Redirect::back()->with('success', "Following $user->name");
    }

    public function delete(User $user): RedirectResponse
    {
        if (Auth::user()->id === $user->id) {
            return Redirect::back()->with('error', 'You cannot unfollow yourself');
        }

        Auth::user()->follows()->detach($user);

        return Redirect::back()->with('success', "Unfollowing $user->name");
    }
}

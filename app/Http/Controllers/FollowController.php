<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;

class FollowController extends Controller
{
    public function store(User $user)
    {
        Auth::user()->follows()->attach($user);
    }

    public function delete(User $user)
    {
        Auth::user()->follows()->detach($user);
    }
}

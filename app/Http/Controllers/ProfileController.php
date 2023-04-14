<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return Inertia::render('Profile/Show');
    }
}

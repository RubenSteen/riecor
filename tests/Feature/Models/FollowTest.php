<?php

use App\Models\User;

it('can retrieve a list the user follows', function () {
    $user = User::factory()->create();

    $usersToFollow = User::factory()->count(3)->create();

    $user->follows()->attach($usersToFollow);

    expect($user->follows)->toHaveCount(3);
});

it('can retrieve a list of the user their followers', function () {
    $user = User::factory()->create();

    $followers = User::factory()->count(3)->create();

    foreach ($followers as $follower) {
        $follower->follows()->attach($user);
    }

    expect($user->followers)->toHaveCount(3);
});

it('can retrieve the first user it follows', function () {
    $user = User::factory()->create();

    $usersToFollow = User::factory()->count(3)->create();

    $user->follows()->attach($usersToFollow);

    expect($user->follows->first()->email)->toBe($usersToFollow->first()->email);
});

it('can retrieve the created_at from the pivot table', function () {
    $user = User::factory()->create();

    $usersToFollow = User::factory()->count(3)->create();

    $user->follows()->attach($usersToFollow);

    expect($user->follows()->first()->pivot->created_at)->toBeInstanceOf(Illuminate\Support\Carbon::class);
});

it('can retrieve the updated_at from the pivot table', function () {
    $user = User::factory()->create();

    $usersToFollow = User::factory()->count(3)->create();

    $user->follows()->attach($usersToFollow);

    expect($user->follows()->first()->pivot->updated_at)->toBeInstanceOf(Illuminate\Support\Carbon::class);
});

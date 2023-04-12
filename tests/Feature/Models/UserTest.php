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

it('can retrieve the timestamps from the pivot table', function (string $column) {
    $user = User::factory()->create();

    $usersToFollow = User::factory()->count(3)->create();

    $user->follows()->attach($usersToFollow);

    expect($user->follows()->first()->pivot->$column)->toBeInstanceOf(Illuminate\Support\Carbon::class);
})->with(['created_at', 'updated_at']);

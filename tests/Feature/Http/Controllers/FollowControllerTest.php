<?php

use App\Models\User;
use function Pest\Laravel\{actingAs};

it('can follow a user', function () {
    $usersToFollow = User::factory()
        ->count(10)
        ->create();

    actingAs($user = User::factory()->create())
        ->post(route('follow.store', $usersToFollow->first()->id))
        ->assertStatus(200);

    expect($user->fresh()->follows)
        ->toHaveCount(1);

    expect($user->fresh()->follows->first()->email)
        ->toBe($usersToFollow->first()->email);
});

it('can unfollow a user', function () {
    $usersToFollow = User::factory()
        ->count(10)
        ->create();

    $user = User::factory()->create();

    $user->follows()->attach($usersToFollow);

    actingAs($user)
        ->delete(route('follow.delete', $usersToFollow->first()->id))
        ->assertStatus(200);

    expect($user->fresh()->follows)
        ->toHaveCount(9);

    expect($user->fresh()->follows->first()->email)
        ->not
        ->toBe($usersToFollow->first()->email);
});

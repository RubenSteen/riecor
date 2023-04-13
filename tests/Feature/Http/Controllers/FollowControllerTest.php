<?php

use App\Models\User;
use function Pest\Laravel\{actingAs};

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->usersToFollow = User::factory()
        ->count(10)
        ->create();

    // The user will always have followers
    $this->user->follows()->attach($this->usersToFollow);
});

it('can follow a user', function () {
    actingAs($this->user)
        ->post(route('follow.store', $usertoFollow = User::factory()->create()))
        ->assertStatus(302)
        ->assertSessionHas(['success' => "Following {$usertoFollow->name}"]);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count() + 1));

    expect($this->user->fresh()->follows->last()->email)
        ->toBe($usertoFollow->email);
});

it('can unfollow a user', function () {
    actingAs($this->user)
        ->delete(route('follow.delete', $this->usersToFollow->first()))
        ->assertStatus(302)
        ->assertSessionHas(['success' => "Unfollowing {$this->usersToFollow->first()->name}"]);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count() - 1));

    expect($this->user->fresh()->follows->first()->email)
        ->not
        ->toBe($this->usersToFollow->first()->email);
});

it('cannot follow him/herself', function () {
    actingAs($this->user)
        ->post(route('follow.store', $this->user))
        ->assertStatus(302)
        ->assertSessionHas(['error' => 'You cannot follow yourself']);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count()));

    expect($this->user->fresh()->follows->last()->email)
        ->not
        ->toBe($this->user->email);
});

it('cannot unfollow him/herself', function () {
    actingAs($this->user)
        ->delete(route('follow.delete', $this->user))
        ->assertStatus(302)
        ->assertSessionHas(['error' => 'You cannot unfollow yourself']);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count()));
});

it('cannot follow a user their already following', function () {
    $request = actingAs($this->user)
        ->post(route('follow.store', $this->usersToFollow->first()))
        ->assertStatus(302)
        ->assertSessionHas(['error' => "Already following {$this->usersToFollow->first()->name}"]);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count()));
});

it('cannot unfollow a user their not following', function () {
    //Unfollowing the first user.
    $this->user->follows()->detach($unfollow = $this->usersToFollow->first());

    $request = actingAs($this->user)
        ->delete(route('follow.delete', $unfollow))
        ->assertStatus(302)
        ->assertSessionHas(['error' => "You are not following {$unfollow->name}"]);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count() - 1));
});

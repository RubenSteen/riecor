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
        ->post(route('follow.store', $this->usersToFollow->first()->id))
        ->assertStatus(302)
        ->assertSessionHas(['success' => "Following {$this->usersToFollow->first()->name}"]);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count() + 1));

    expect($this->user->fresh()->follows->first()->email)
        ->toBe($this->usersToFollow->first()->email);
});

it('can unfollow a user', function () {
    actingAs($this->user)
        ->delete(route('follow.delete', $this->usersToFollow->first()->id))
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
        ->post(route('follow.store', $this->user->id))
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
        ->delete(route('follow.delete', $this->user->id))
        ->assertStatus(302)
        ->assertSessionHas(['error' => 'You cannot unfollow yourself']);

    expect($this->user->fresh()->follows)
        ->toHaveCount(($this->usersToFollow->count()));
});

todo('You cannot follow a user you are already following');

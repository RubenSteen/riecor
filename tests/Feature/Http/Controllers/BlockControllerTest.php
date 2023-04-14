<?php

use App\Models\User;
use function Pest\Laravel\{actingAs};

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->usersToBlock = User::factory()
        ->count(10)
        ->create();

    // The user will always have blockers
    $this->user->blocks()->attach($this->usersToBlock);
});

it('can block a user', function () {
    actingAs($this->user)
        ->post(route('block.store', $usertoBlock = User::factory()->create()))
        ->assertStatus(302)
        ->assertSessionHas(['success' => "Blocked {$usertoBlock->name}"]);

    expect($this->user->fresh()->blocks)
        ->toHaveCount(($this->usersToBlock->count() + 1));

    expect($this->user->fresh()->blocks->last()->email)
        ->toBe($usertoBlock->email);
});

it('can unblock a user', function () {
    actingAs($this->user)
        ->delete(route('block.delete', $this->usersToBlock->first()))
        ->assertStatus(302)
        ->assertSessionHas(['success' => "Unblocked {$this->usersToBlock->first()->name}"]);

    expect($this->user->fresh()->blocks)
        ->toHaveCount(($this->usersToBlock->count() - 1));

    expect($this->user->fresh()->blocks->first()->email)
        ->not
        ->toBe($this->usersToBlock->first()->email);
});

it('cannot block him/herself', function () {
    actingAs($this->user)
        ->post(route('block.store', $this->user))
        ->assertStatus(302)
        ->assertSessionHas(['error' => 'You cannot block yourself']);

    expect($this->user->fresh()->blocks)
        ->toHaveCount(($this->usersToBlock->count()));

    expect($this->user->fresh()->blocks->last()->email)
        ->not
        ->toBe($this->user->email);
});

it('cannot unblock him/herself', function () {
    actingAs($this->user)
        ->delete(route('block.delete', $this->user))
        ->assertStatus(302)
        ->assertSessionHas(['error' => 'You cannot unblock yourself']);

    expect($this->user->fresh()->blocks)
        ->toHaveCount(($this->usersToBlock->count()));
});

it('cannot block a user their already blocking', function () {
    $request = actingAs($this->user)
        ->post(route('block.store', $this->usersToBlock->first()))
        ->assertStatus(302)
        ->assertSessionHas(['error' => "Already blocking {$this->usersToBlock->first()->name}"]);

    expect($this->user->fresh()->blocks)
        ->toHaveCount(($this->usersToBlock->count()));
});

it('cannot unblock a user their not blocking', function () {
    //Unfollowing the first user.
    $this->user->blocks()->detach($unblocked = $this->usersToBlock->first());

    $request = actingAs($this->user)
        ->delete(route('block.delete', $unblocked))
        ->assertStatus(302)
        ->assertSessionHas(['error' => "You are not blocking {$unblocked->name}"]);

    expect($this->user->fresh()->blocks)
        ->toHaveCount(($this->usersToBlock->count() - 1));
});

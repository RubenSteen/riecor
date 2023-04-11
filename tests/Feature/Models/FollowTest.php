<?php

use App\Models\User;

it('can retrieve a list the user follows', function () {
    $this->actingAs($user = User::factory()->create());
    
    $user->hasFollows(User::factory()->create(3));

    expect($user->fresh()->follows)->toHaveCount(3);
});

it('can retrieve a list of the user their followers', function () {
    
});
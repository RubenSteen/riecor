<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('a guest can make a request to the homepage', function () {
    get('/')->assertStatus(200);
});

test('a authenticated user can make a request to the homepage', function () {
    actingAs(User::factory()->create())
        ->get('/')
        ->assertStatus(200);
});

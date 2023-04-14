<?php

use App\Models\User;
use Carbon\Carbon;
use function Pest\Laravel\{actingAs};

/*
|--------------------------------------------------------------------------
| Last Activity tests
|--------------------------------------------------------------------------
*/

it('updates the last_activity record when a user makes a request', function () {
    $user = User::factory()->create(); // Observer 'creating' bypass
    $user->last_activity = now()->subDay();
    $user->save();

    actingAs($user)
        ->get('/')
        ->assertStatus(200);

    expect(Carbon::parse($user->fresh()->last_activity)->diffInMinutes(now()))
        ->toBe(0);
});

/*
|--------------------------------------------------------------------------
| Followers tests
|--------------------------------------------------------------------------
*/

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

it('can retrieve the timestamps from the pivot table user_follows', function (string $column) {
    $user = User::factory()->create();

    $usersToFollow = User::factory()->count(3)->create();

    $user->follows()->attach($usersToFollow);

    expect($user->follows()->first()->pivot->$column)->toBeInstanceOf(Illuminate\Support\Carbon::class);
})->with(['created_at', 'updated_at']);

/*
|--------------------------------------------------------------------------
| Views tests
|--------------------------------------------------------------------------
*/

it('can retrieve a list the user viewed', function () {
    $user = User::factory()->create();

    $usersToView = User::factory()->count(3)->create();

    $user->views()->attach($usersToView);

    expect($user->views)->toHaveCount(3);
});

it('can retrieve a list of the user their viewers', function () {
    $user = User::factory()->create();

    $viewers = User::factory()->count(3)->create();

    foreach ($viewers as $viewer) {
        $viewer->views()->attach($user);
    }

    expect($user->viewers)->toHaveCount(3);
});

it('can retrieve the first user it viewed', function () {
    $user = User::factory()->create();

    $usersToView = User::factory()->count(3)->create();

    $user->views()->attach($usersToView);

    expect($user->views->first()->email)->toBe($usersToView->first()->email);
});

it('can retrieve the timestamps from the pivot table profile_viewers', function (string $column) {
    $user = User::factory()->create();

    $usersToView = User::factory()->count(3)->create();

    $user->views()->attach($usersToView);

    expect($user->views()->first()->pivot->$column)->toBeInstanceOf(Illuminate\Support\Carbon::class);
})->with(['created_at', 'updated_at']);

todo('If the user never viewed the profile before it marks the view unique');

todo('Every view after the first unique view, will be not unique');

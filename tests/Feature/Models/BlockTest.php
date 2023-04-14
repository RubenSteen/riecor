<?php

use App\Models\User;
use Carbon\Carbon;
use function Pest\Laravel\{actingAs};

/*
|--------------------------------------------------------------------------
| Blocking tests
|--------------------------------------------------------------------------
*/

it('can retrieve a list the user blocks', function () {
    $user = User::factory()->create();

    $usersToBlock = User::factory()->count(3)->create();

    $user->blocks()->attach($usersToBlock);

    expect($user->blocks)->toHaveCount(3);
});

it('can retrieve a list of the user their blockers', function () {
    $user = User::factory()->create();

    $blockers = User::factory()->count(3)->create();

    foreach ($blockers as $blocker) {
        $blocker->blocks()->attach($user);
    }

    expect($user->blockers)->toHaveCount(3);
});

it('can retrieve the first user it blocks', function () {
    $user = User::factory()->create();

    $usersToBlock = User::factory()->count(3)->create();

    $user->blocks()->attach($usersToBlock);

    expect($user->blocks->first()->email)->toBe($usersToBlock->first()->email);
});

it('can retrieve the timestamps from the pivot table', function (string $column) {
    $user = User::factory()->create();

    $usersToBlock = User::factory()->count(3)->create();

    $user->blocks()->attach($usersToBlock);

    expect($user->blocks()->first()->pivot->$column)->toBeInstanceOf(Illuminate\Support\Carbon::class);
})->with(['created_at', 'updated_at']);

/*
|--------------------------------------------------------------------------
| Blocking tests > Middleware test
|--------------------------------------------------------------------------
*/

todo('Cannot message a user that blocks you');

todo('Cannot be messaged by a user that you block');

todo('Cannot comment on uploads from a user that blocks you');

todo('Cannot get comments from a user on uploads that you block');
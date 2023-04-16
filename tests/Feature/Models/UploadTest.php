<?php

use App\Models\Upload;
use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = User::factory()->create();
});

/*
|--------------------------------------------------------------------------
| Upload tests
|--------------------------------------------------------------------------
*/

it('can create a upload', function () {
    Upload::unguard(); // Bypass the fillable error

    $data = [
        'user_id' => $this->user->id,
        'name' => 'Some name',
        'original_name' => 'image.jpg',
    ];

    $upload = Upload::create($data);

    expect($upload->fresh()->first())->toMatchArray($data);
});

it('generates a unique filename when a uploading a file', function () {
    $upload = Upload::factory()->create();

    expect(Str::isUuid($upload->fresh()->first()->file_name))->toBeTrue();
});

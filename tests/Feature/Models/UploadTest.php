<?php

use App\Models\Upload;
use App\Models\User;

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
        'original_extension' => '.jpg',
        'original_size' => '652442',
        'original_mime_type' => 'image/png',
    ];

    $upload = Upload::create($data);

    expect($upload->fresh()->first())->toMatchArray($data);
});

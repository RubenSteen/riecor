<?php

use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\{actingAs};

/*
|--------------------------------------------------------------------------
| Upload tests
|--------------------------------------------------------------------------
*/

it('can upload a file', function () {
    Storage::fake('local');

    $file_name = 'new-upload.jpg';
    $upload_title = 'Some adjusted name';

    $file = UploadedFile::fake()->image($file_name);

    $response = actingAs(User::factory()->create())
        ->post(route('upload.store'), [
            'file' => $file,
            'name' => $upload_title,
        ])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('uploads', [
        'original_name' => $file_name,
    ]);

    $upload = Upload::first();

    expect($upload->name)->toEqual($upload_title);

    Storage::disk('media')->assertExists($upload->path);

    Storage::disk('media')->assertMissing($file_name);
});

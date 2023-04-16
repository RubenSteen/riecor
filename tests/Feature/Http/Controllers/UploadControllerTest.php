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
    // Faking the Storage
    Storage::fake('local');

    // Faking the file
    $file_name = 'new-upload.jpg';
    $file = UploadedFile::fake()->image($file_name);

    $response = actingAs(User::factory()->create())
        ->post(route('upload.store'), [
            'file' => $file,
            'name' => 'Some adjusted name',
        ])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('uploads', [
        'original_name' => $file_name,
    ]);

    $upload = Upload::first();
    $upload->file->assertNotNull();

    // Assert the file was stored...
    Storage::disk('local')->assertExists($file_name);

    // Assert a file does not exist...
    Storage::disk('local')->assertMissing('missing.jpg');
});

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadRequest;
use App\Http\Requests\UpdateUploadRequest;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('upload');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUploadRequest $request)
    {
        $user = \Auth::user();

        $path = $this->generatePath($user);

        $file = $request->file('file');
        $upload = Upload::create([
            'name' => $request->name,
            'path' => $path,
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'original_extension' => $file->getClientOriginalExtension(),
            'original_size' => $file->getSize(),
            'original_mime_type' => $file->getMimeType(),
        ]);

        Storage::disk('media')->putFileAs($upload->path, $file, Str::after($upload->path, "{$path}/"));

        return Redirect::route('upload.create')->with(['success' => 'Uploaded']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Upload $upload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUploadRequest $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upload $upload)
    {
        //
    }

    private function generatePath(User $user)
    {
        $time = now();

        return "$time->year/$time->month/$user->id";
    }
}

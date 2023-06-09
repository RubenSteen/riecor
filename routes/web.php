<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UploadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::post('/follow/{user}', [FollowController::class, 'store'])->middleware('auth')->name('follow.store');
Route::delete('/follow/{user}', [FollowController::class, 'delete'])->middleware('auth')->name('follow.delete');

Route::post('/block/{user}', [BlockController::class, 'store'])->middleware('auth')->name('block.store');
Route::delete('/block/{user}', [BlockController::class, 'delete'])->middleware('auth')->name('block.delete');

Route::get('/upload/create', [UploadController::class, 'create'])->middleware('auth')->name('upload.create');
Route::post('/upload', [UploadController::class, 'store'])->middleware('auth')->name('upload.store');

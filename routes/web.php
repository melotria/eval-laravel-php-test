<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

// Chat Helper route - no auth required
Route::get('/chat-helper', function () {
    return view('chat-helper');
})->name('chat-helper');

// Chat API endpoint
Route::post('/chat-helper/send', 'App\Http\Controllers\ChatHelperController@sendMessage')->name('chat-helper.send');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

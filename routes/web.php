<?php

use App\Http\Controllers\ShortenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::view('/', 'landing');

Route::post('/shorten', [ShortenController::class, 'store'])->name('shorten.store');
Route::get('/processing/{token}', [ShortenController::class, 'processing'])->name('shorten.processing');
Route::post('/processing/{token}/suggest', [ShortenController::class, 'suggest'])
    ->name('shorten.suggest')
    ->middleware('throttle:20,1');
Route::get('/result/{token}', [ShortenController::class, 'result'])->name('shorten.result');
Route::post('/result/{token}/choose', [ShortenController::class, 'choose'])->name('shorten.choose');
Route::get('/created/{code}', [ShortenController::class, 'created'])->name('shorten.created');
Route::get('/{code}', [RedirectController::class, 'show'])
    ->where('code', '[a-z0-9-]+')
    ->name('redirect');

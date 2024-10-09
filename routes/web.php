<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', [SearchController::class, 'showSearchPage'])
    ->name('search');

Route::post('/', [SearchController::class, 'search'])
    ->name('search.result');

Route::get('/register', [RegistrationController::class, 'create'])
    ->name('register');

Route::post('/register', [RegistrationController::class, 'store'])
    ->middleware(ProtectAgainstSpam::class)
    ->name('register.store');

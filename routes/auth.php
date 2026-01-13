<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| Livewire Auth Components
|--------------------------------------------------------------------------
*/
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Auth\ConfirmPassword;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/register', Register::class)->name('register');

    Route::get('/login', Login::class)->name('login');

    Route::get('/forgot-password', ForgotPassword::class)
        ->name('password.request');

    Route::get('/reset-password/{token}', ResetPassword::class)
        ->name('password.reset');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/verify-email', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('/confirm-password', ConfirmPassword::class)
        ->name('password.confirm');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppController;



Route::middleware(['auth'])->group(function(){
    Route::get('/', [AppController::class, 'home']);
    Route::get('/search', [AppController::class, 'search']);
    Route::get('/check/{mediaType}/{id}', [AppController::class, 'check']);
    Route::post('/add-dvd', [AppController::class, 'addDvd']);
    Route::get('/dvd/{id}', [AppController::class, 'edit']);
    Route::post('/dvd/{id}', [AppController::class, 'save']);
    Route::get('/dvd/{id}/delete', [AppController::class, 'delete']);

    Route::get('/settings', [AppController::class, 'settings']);
    Route::post('/settings', [AppController::class, 'settingsSave']);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
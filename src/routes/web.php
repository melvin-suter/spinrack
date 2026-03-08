<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppController;


Route::middleware(['auth'])->group(function(){
    Route::get('/', [AppController::class, 'home']);
    Route::post('/add-dvd', [AppController::class, 'addDvd']);
    Route::get('/check/{mediaType}/{id}', [AppController::class, 'check']);

    Route::get('/library', [AppController::class, 'library']);
    Route::get('/jobs', [AppController::class, 'jobs']);
    Route::get('/dvd/{id}', [AppController::class, 'show']);
    Route::get('/dvd/{id}/edit', [AppController::class, 'edit']);
    Route::get('/dvd/{id}/requeue', [AppController::class, 'requeue']);
    Route::post('/dvd/{id}/edit', [AppController::class, 'save']);
    Route::get('/import', [AppController::class, 'import']);
    Route::post('/import', [AppController::class, 'runImport']);
    Route::get('/dvd/{id}/delete', [AppController::class, 'delete']);


    Route::get('/settings', [AppController::class, 'settings']);
    Route::post('/settings', [AppController::class, 'settingsSave']);

    


    

    Route::get('/search', [AppController::class, 'search']);

    Route::get('/settings', [AppController::class, 'settings']);
    Route::post('/settings', [AppController::class, 'settingsSave']);

    Route::get('/tag/{id}', [AppController::class, 'showTag']);

    Route::get('/collection/{id}', [AppController::class, 'collection']);


    Route::get('/show/{id}', [AppController::class, 'show']);
    Route::get('/rnd/{id}', [AppController::class, 'rnd']);

});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
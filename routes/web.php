<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;

Route::get('/dashboard', [ServerController::class, 'index'])->name('dashboard');

Route::get('/', function () {
    return view('welcome');
});

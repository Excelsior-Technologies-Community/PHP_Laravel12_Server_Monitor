<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ServerController::class, 'index'])->name('dashboard');
Route::post('/hosts', [ServerController::class, 'store'])->name('hosts.store');
Route::get('/hosts', function () {
    return redirect()->route('dashboard');
});
use App\Http\Controllers\Controller;
Route::get('/api/server-status', [ServerController::class, 'getStatusData']);
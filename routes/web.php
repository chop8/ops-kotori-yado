<?php

use App\Http\Controllers\ReceptionController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/unlock', [App\Http\Controllers\AuthController::class, 'unlock']);
Route::post('/auth/lock', [App\Http\Controllers\AuthController::class, 'lock']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reception/', [ReceptionController::class, 'index'])
    ->name('reception.index');

Route::get('/reception/{date}', [ReceptionController::class, 'schedule'])
    ->name('reception.show')
    ->where('date', '\d{4}-\d{1,2}-\d{1,2}');

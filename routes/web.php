<?php

use App\Http\Controllers\ReceptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reception/', [ReceptionController::class, 'index'])
    ->name('reception.index');

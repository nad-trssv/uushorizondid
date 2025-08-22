<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function () {
    return view('home');
});
Route::get('{page}', [IndexController::class, '__invoke'])->where('page', '.*');

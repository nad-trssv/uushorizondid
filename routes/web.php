<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

// Route::get('/welcome', function () {
//     return view('home');
// });
Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/blog', [IndexController::class, 'blog'])->name('blog');
Route::get('{page}', [IndexController::class, '__invoke'])->where('page', '.*');
<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Route::get('/welcome', function () {
//     return view('home');
// });
Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('{page}', [IndexController::class, '__invoke'])->where('page', '.*');
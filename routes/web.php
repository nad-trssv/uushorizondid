<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Route::get('/welcome', function () {
//     return view('home');
// });
Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comment', [BlogController::class, 'storeComment'])
    ->name('blog.comment.store');

Route::get('/event', [EventController::class, 'index'])->name('events.index');


// admin panel
Route::get('{page}', [IndexController::class, '__invoke'])->where('page', '.*');
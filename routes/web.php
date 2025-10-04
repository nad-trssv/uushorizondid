<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{EventController, IndexController, BlogController};

$webRoutes = function () {
    Route::get('/', [IndexController::class, 'index'])->name('home');
    Route::get('/contact', [IndexController::class, 'contact'])->name('contact');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::post('/blog/{slug}/comment', [BlogController::class, 'storeComment'])->name('blog.comment.store');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('/events/feed', [EventController::class, 'feed'])->name('events.feed');
    Route::post('/events/register', [EventController::class, 'register'])->name('events.register');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
};

// 1) С префиксом — RU/EN/UK (locale обязателен)
$prefixed = implode('|', config('locales.prefixed', ['ru','en','uk']));
Route::group([
    'prefix'     => '{locale}',
    'where'      => ['locale' => $prefixed],
    'middleware' => ['setlocale'],
], function () use ($webRoutes) {
    Route::name('loc.')->group($webRoutes); // <-- ДАЁМ ПРЕФИКС ИМЕНИ
});

// 2) Без префикса — только default (ET)
Route::middleware('setlocale')->group($webRoutes);


// admin panel
Route::get('{page}', [IndexController::class, '__invoke'])->where('page', '.*');
<?php

use App\Http\Controllers\Api\V1\AppointmentsController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SiteSettingController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WorkTimeExceptionController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => 'api'], function ($router) {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', [AuthController::class, 'login']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::post('me', [AuthController::class, 'me']);
            Route::post('register', [AuthController::class, 'register']);
        });

        Route::group(['middleware' => 'jwt.auth'], function ($router) {
            Route::group(['prefix' => 'services'], function () {
                Route::get('/', [ServiceController::class, 'index'])
                    ->name('api.services.index');
                Route::get('/{service}', [ServiceController::class, 'show'])
                    ->name('api.services.show');
                Route::post('/', [ServiceController::class, 'store'])
                    ->name('api.services.store');
                Route::put('/{service}', [ServiceController::class, 'update'])
                    ->name('api.services.update');
                Route::delete('/{service}', [ServiceController::class, 'destroy'])
                    ->name('api.services.destroy');
                Route::put('/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])
                    ->name('api.services.toggle-status');
                Route::post('/{service}/add-master', [ServiceController::class, 'addMaster'])
                    ->name('api.services.add-master');
                Route::post('/{service}/remove-master', [ServiceController::class, 'removeMaster'])
                ->name('api.services.remove-master');
            });
            Route::group(['prefix' => 'categories'], function () {
                Route::get('/', [CategoryController::class, 'index'])
                    ->name('api.categories.index');
                Route::get('/{category}', [CategoryController::class, 'show'])
                    ->name('api.categories.show');
                Route::post('/move', [CategoryController::class, 'move'])
                    ->name('api.categories.move');
                Route::post('/', [CategoryController::class, 'store'])
                    ->name('api.categories.store');
            });
            Route::group(['prefix' => 'settings'], function () {
                Route::get('/', [SiteSettingController::class, 'index'])
                    ->name('api.settings.index');
                Route::get('/{group}', [SiteSettingController::class, 'show'])
                    ->name('api.settings.show');
                Route::put('/{group}', [SiteSettingController::class, 'update'])
                    ->name('api.settings.update');
            });
            Route::prefix('work-time-exceptions')->group(function () {
                Route::get('/', [WorkTimeExceptionController::class, 'index']);  
                Route::get('/{id}', [WorkTimeExceptionController::class, 'show']);  
                Route::post('/', [WorkTimeExceptionController::class, 'store']);        
                Route::put('/{id}', [WorkTimeExceptionController::class, 'update']);   
                Route::delete('/{id}', [WorkTimeExceptionController::class, 'destroy']); 
            });
            Route::group(['prefix' => 'events'], function () {
                Route::get('/', [EventController::class, 'index']);
                Route::get('/stats', [EventController::class, 'stats']);
                Route::post('/', [EventController::class, 'store']);
                Route::get('/calendar', [EventController::class, 'calendarEvents']);
                Route::post('/upload-image', [EventController::class, 'uploadImage']);
                Route::get('/{event}', [EventController::class, 'show']);
                Route::put('/{event}', [EventController::class, 'update']);
                Route::delete('/{event}', [EventController::class, 'destroy']);

                Route::post('/{event}/gallery', [EventController::class, 'uploadGallery']);              
                Route::put('/{event}/gallery/{gallery}', [EventController::class, 'updateGallery']); 
                Route::delete('/{event}/gallery/{gallery}', [EventController::class, 'destroyGallery']); 
            });
            Route::group(['prefix' => 'appointments'], function () {
                Route::get('/all', [AppointmentsController::class, 'index'])
                    ->name('api.appointments.index');
                Route::get('/{appointments}', [AppointmentsController::class, 'show'])
                    ->name('api.appointments.show');
                Route::get('/', [AppointmentsController::class, 'userAppointments'])
                    ->name('api.appointments.userAppointments');
            });
            Route::group(['prefix' => 'users'], function () {
                Route::get('/masters', [UserController::class, 'masters'])
                    ->name('api.users.masters');
            });
            Route::group(['prefix' => 'posts'], function () {
                Route::get('/', [PostController::class, 'index'])
                    ->name('api.posts.index');
                Route::get('/stats', [PostController::class, 'stats'])
                    ->name('api.posts.stats');
                Route::get('/{post}', [PostController::class, 'show'])
                    ->name('api.posts.show');
                Route::post('/upload-image', [PostController::class, 'uploadImage']);
                Route::post('/', [PostController::class, 'store'])
                    ->name('api.posts.store');
                Route::put('/{post}', [PostController::class, 'update'])
                    ->name('api.posts.update');
                Route::delete('/{post}', [PostController::class, 'destroy'])
                    ->name('api.posts.destroy');
            });
        });
    });
//TODO: Add view for appointments
});
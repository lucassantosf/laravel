<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong', 'timestamp' => now()]);
});

Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:api', 'check_permission']], function () {
    Route::group(['prefix' => 'usuario', 'as' => 'usuario.'], function () {
        Route::get('me', [AuthController::class, 'me'])->name('me');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::group(['controller' => \App\Http\Controllers\UserController::class], function () {
            Route::get('', 'index')->name('index');
            Route::get('{id}', 'show')->name('show');
            Route::post('', 'store')->name('store');
            Route::post('{id}', 'update')->name('update');
            Route::delete('{id}', 'destroy')->name('destroy');
        });
    });

    Route::group(['prefix' => 'post', 'as' => 'post.', 'controller' => \App\Http\Controllers\PostController::class], function () {
        Route::get('', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
        Route::post('', 'store')->name('store');
        Route::post('{id}', 'update')->name('update');
        Route::post('{id}/job', 'job')->name('job');
        Route::delete('{id}', 'destroy')->name('destroy');
    });

    Route::group(['prefix' => 'report', 'as' => 'report.', 'controller' => ReportController::class], function () {
        Route::get('example', 'example')->name('example');
    });
});

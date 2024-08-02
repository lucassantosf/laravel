<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('ping', function(){
    return 'pong';
});

Route::post('/login', [AuthController::class,'login']);

Route::group(['middleware'=>['auth:api','check_permission']],function(){

    Route::group(['prefix'=>'debugs','as'=>'debugs.'],function(){
        Route::get('sqs', [DebugController::class,'sqs'])->name('sqs');
        Route::get('sns', [DebugController::class,'sns'])->name('sns');
        Route::get('event', [DebugController::class,'event'])->name('event');
    });

    Route::group(['prefix'=>'usuario','as'=>'usuario.'],function(){
        Route::get('me', [AuthController::class,'me'])->name('me');
        Route::post('logout', [AuthController::class,'logout'])->name('logout');     

        Route::group(['controller'=>UserController::class],function(){
            Route::get('', 'index')->name('index'); 
            Route::get('{id}', 'show')->name('show'); 
            Route::post('', 'store')->name('store'); 
            Route::post('{id}', 'update')->name('update'); 
            Route::delete('{id}', 'destroy')->name('destroy'); 
        });
    });

    Route::group(['prefix'=>'post','as'=>'post.','controller'=>PostController::class],function(){
        Route::get('', 'index')->name('index'); 
        Route::get('{id}', 'show')->name('show'); 
        Route::post('', 'store')->name('store'); 
        Route::post('{id}', 'update')->name('update'); 
        Route::post('{id}/job', 'job')->name('job'); 
        Route::delete('{id}', 'destroy')->name('destroy'); 
    });

    Route::group(['prefix'=>'report','as'=>'report.','controller'=>ReportController::class],function(){
        Route::get('example', 'example')->name('example');
    });
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController; 
use App\Http\Controllers\ReportController; 
use App\Http\Controllers\SerproController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/teste', function(){
    $t = DB::table('ADM_ATUALIZACAO_SISTEMA')->get();
    dd($t);
    return true;
});

Route::post('/login', [AuthController::class,'login']);

Route::group(['middleware'=>['auth:api','chech_permission']],function(){

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

    Route::group(['prefix'=>'document','as'=>'document.','controller'=>SerproController::class],function(){
        Route::post('validate', 'validate_document')->name('validate'); 
    });

});
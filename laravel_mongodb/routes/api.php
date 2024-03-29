<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController; 
use App\Http\Controllers\RoleController; 
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

Route::get('/ping', function(){
    return 'pong';
    //insert example
    //$user = \App\Mongo\User::create(['name'=>'username']);
    //$resource = \App\Mongo\Carga::create(['title'=>'title 123','user_id'=>$user->id_]);
    // return $resource;

    //index
    // $resource = \App\Mongo\Carga::orderBy('created_at','DESC')->get();
    $resource = \App\Models\Role::orderBy('created_at','DESC')->get();
    return $resource;

    //show
    // $resource = \App\Mongo\Carga::where('_id','63c86a1e2f6d4d4c980f9ca5')->first();
    // return $resource;

    //update
    // $resource = \App\Mongo\Carga::where('_id','63c86a1e2f6d4d4c980f9ca5')->first();
    // $resource->update(['title'=>'title updated']);
    // return $resource;

    //delete
    $resource = \App\Mongo\Carga::where('_id','63c86a1e2f6d4d4c980f9ca5')->delete();
    return 'destroyed success '.$resource;
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

    Route::group(['prefix'=>'permission','as'=>'permission.'],function(){
        Route::group(['controller'=>PermissionController::class],function(){
            Route::get('', 'index')->name('index'); 
        });
    });

    Route::group(['prefix'=>'role','as'=>'role.'],function(){
        Route::group(['controller'=>RoleController::class],function(){
            Route::get('', 'index')->name('index'); 
        });
    });
});
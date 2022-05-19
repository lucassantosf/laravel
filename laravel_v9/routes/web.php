<?php

use App\Jobs\ExampleJob;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('users', function () {
    return User::all();
});

Route::get('create-job', function () {
    // dispatch(new ExampleJob('lucas','lucas@gmail.com','123456'));
    ExampleJob::dispatch('lucas','lucas@gmail.com','123456');
    return 'created';
});

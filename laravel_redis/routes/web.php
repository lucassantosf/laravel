<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Jobs\FirstJob;

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

Route::get('/', function (Request $request) {

    // $request->session()->put('username','lucas');
    // $request->session()->save();
    // dispatch( new FirstJob()->onQueue('default')->onConnection('redis') );
    FirstJob::dispatch()->onQueue('DEFAULT')->onConnection('redis');
    return 'ok';

    // return view('welcome');

    // $a = $request->session()->get('username');
    // dd('a',$a);

    $queue = Queue::push('LogMessage',array('message'=>'Time: '.time()));
    return $queue;
});

<?php

use App\Jobs\ExampleJob;
use App\Models\User;
use App\Http\Controllers\{
    UserController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

Route::get('/users', [UserController::class,'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class,'show'])->name('users.show');

Route::get('create-job', function () {
    ExampleJob::dispatch('lucas','lucas@gmail.com',Hash::make('123456'));
    return 'created';
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        // 'device_name' => 'required',//pode ser o segredo
    ]);
 
    $user = User::where('email', $request->email)->first();

    if (! $user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken('segredo')->plainTextToken;
})->name('create.token');
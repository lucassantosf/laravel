<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/posts/{post_id}', [PostController::class, 'show']);

Route::post('/posts', [PostController::class, 'store']);

Route::apiResource('posts', PostController::class)->except(['show','store']);
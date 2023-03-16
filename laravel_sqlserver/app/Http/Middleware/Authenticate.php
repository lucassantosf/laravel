<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    { 
        if($request->is('api/*')){ 
            throw new HttpResponseException(response()->json('Unauthorized', 401));  
        }else{
            return route('login');
        } 
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        if($request->is('api/*')){ 
            throw new HttpResponseException(response()->json('Unauthorized', 401));  
        }else{
            return route('login');
        }
    }
}

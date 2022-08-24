<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
class Authenticate extends Middleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // public function handle($request, Closure $next, $guard = null)
    // {
    //     if ($this->auth->guard($guard)->guest()) {
    //         return response('Unauthorized.', 401);
    //     }

    //     return $next($request);
    // }

    protected function redirectTo($request)
    {
    //     // if ($this->auth->guard($guard)->guest()) {
    //         // return response('Unauthorized.', 401)->headers();
    //     // }
    // // {
    // //     if ($this->auth->guard('api')->guest()) {
    // //         return response('Unauthorized.', 401);
    // //     }
    // //     return $next($request);

        if($request->is('api/*'))
        {
            throw new HttpResponseException(response()->error(['failure_reason'=>'Fresh Access Token Required'], 'Unauthorized Request', 401));  
        }

        if (! $request->expectsJson()) {
            // throw new \Exception("Unauthorized", 401);
            // return response()->json(['error' => ''], 401);
            // return response('Unauthorized.', 401);
            return route('login');
        }
    }
}

<?php
namespace App\Http\Middleware;

use Closure; 
use App\Models\User;

class CheckPermissionMiddleware
{
    public function handle($request, Closure $next)
    {        
        $route_action_as = $request->route()[1]['as'];
         
        if(!$request->user()->can($route_action_as)){
            return response()->json(['This resouce is not allowed for this user'], 401); 
        }

        return $next($request); 
    }
}
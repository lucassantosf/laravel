<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();

        // If the route has no name, we allow the request or handle it accordingly
        if (!$routeName) {
            return $next($request);
        }

        if (!$request->user() || !$request->user()->can($routeName)) {
            return response()->json([
                'message' => 'This resource is not allowed for this user',
                'permission_required' => $routeName
            ], 403); // Change to 403 Forbidden which is more semantic for ACL
        }

        return $next($request);
    }
}

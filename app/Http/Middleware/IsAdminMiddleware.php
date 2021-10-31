<?php
/*
 * Author: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Http\Middleware;

use App\Constant\RoleConstant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && auth()->user()->hasRole([RoleConstant::ADMIN], RoleConstant::SUPER_ADMIN)){
            return $next($request);
        }
        return response()->json([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'Forbidden',
            'data' => []
        ], Response::HTTP_FORBIDDEN);
    }
}

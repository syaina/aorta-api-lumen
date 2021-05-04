<?php

namespace App\Http\Middleware;

use App\Admin;
use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            $api_key = explode(' ', $request->header('Authorization'));
            $user = Admin::where('token', $api_key[1])->first();

            if(!$user) {
                return response('Unauthorized', 401);
            } else {
                return $next($request);
            }
        } else {
            return response('Silakan masukkan token', 401);
        }
        // if ($request->header->input('token')) {
        //     $check = User::where('token', $request->header->input('token'))->first();

        //     if(!$check) {
        //         return response('Token tidak valid', 401);
        //     } else {
        //         return $next($request);
        //     } 
        // } else {
        //     return response('Silakan masukkan token', 401);
        // }
    }
}

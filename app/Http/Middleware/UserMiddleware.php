<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;

class UserMiddleware
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
        $cookie = empty($_COOKIE['auth']) ? [] : json_decode(Crypt::decrypt($_COOKIE['auth']), true);

        if(!empty($cookie))
            $user = (new \App\Http\Controllers\UserController())->login(
                $cookie['login'],
                $cookie['pass']
            );
        else
            $user = false;

        if(!$user) {
            return response()->json([
                'error' => 'You are unlogged'
            ], 401);
        } else {
            $request->attributes = ['user' => $user];
            return $next($request);
        }


    }
}

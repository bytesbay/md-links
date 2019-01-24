<?php

namespace App\Http\Middleware;

use Closure;

class ChatMiddleware
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

      (new \App\Message())->isInChat($request->attributes['user']['id_user'], $request->route('id_chat'));
      return $next($request);

    }
}

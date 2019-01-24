<?php

namespace App\Http\Middleware;

use Closure;

class FlowMiddleware
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
    if(!app()->mdb->has('user_flow', [
      'id_flow' => $request->route('id_flow'),
      'id_user' => $request->attributes['user']['id_user'],
    ])) {
      return response()->json([
          'error' => 'You are unlogged'
      ], 401);
    } else {
      return $next($request);
    }
  }
}

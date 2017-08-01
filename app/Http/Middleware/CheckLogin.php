<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CheckLogin
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
        if(Session::has('logged in')){
            return $next($request);
        }
        else{
            return response()->json([
                'error' => 'Not allowed'
            ]);
        }

    }
}

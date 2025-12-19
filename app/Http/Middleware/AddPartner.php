<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddPartner
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
        if($request->has('partner') && !session()->has('order')) {
            session()->put('partner', $request->input('partner'));
        }
        
        return $next($request);
    }
}

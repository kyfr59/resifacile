<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoggingPageActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        activity()
            ->withProperties([
                'ip' => $request->ip(),
                'url' => $request->url()
            ])
            ->event('onClick')
            ->log('Le client visite une nouvelle page');

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;
use Symfony\Component\HttpFoundation\Response;

class PreventRequestsDuringMaintenanceExceptIps extends Middleware
{
    public function handle($request, Closure $next): Response
    {
        $allowedIps = config('security.allowed_ips', []);

        if (in_array($request->ip(), $allowedIps, true)) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
<?php

namespace App\Http\Middleware;

use App\Contracts\Cart;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class VerifySaleProcess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(
            in_array($request->segment(2), [
                'importation',
                'destinataire',
                'expediteur',
                'affranchissement',
                'validation',
                'paiement'
            ]) &&
            ! Session::has('cart')
        ) {
            return redirect()->route('pages.index');
        }

        if (! Session::has('referer')) {
            Session::put('referer');
        }

        Session::put('referer', $request->segment(2));

        return $next($request);
    }
}

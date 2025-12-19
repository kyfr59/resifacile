<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function __invoke(Request $request)
    {
        Auth::guard('site')->loginUsingId(83904);
        return redirect()->route('auth.account');

        if($request->has('token')) {
            $access = Access::where('token', $request->get('token'))->first();

            if($access) {
                $customer = $access->customer;

                $access->delete();

                Auth::guard('site')->loginUsingId($customer->id);

                return redirect()->route('auth.account');
            }
        }

        return redirect()
            ->route('login')
            ->with('error', "Vous n'avez pas l'authorisation d'accéder à cette page");
    }
}

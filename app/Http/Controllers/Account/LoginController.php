<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Mail\UserCreated;
use App\Models\Access;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'exists:customers,email',
            ]
        ]);

        $user = Customer::where('email', $request->input('email'))->firstOrFail();

        Access::where('customer_id', $user->id)->delete();

        $access = new Access();
        $access->token = Str::random(32);
        $access->customer()->associate($user);
        $access->save();

        try {
            Mail::to($user->email)->send(new UserCreated(
                user: $user,
                url: route('authenticate', ['token' => $access->token]),
            ));

            return redirect()
                ->route('login')
                ->with('success', "Rendez-vous dans votre boite mail pour cliquer sur lien contenu dans notre email");
        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->with('error', "Vous n'avez pas l'authorisation d'accéder à cette page");
        }
    }
}

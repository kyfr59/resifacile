<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Mail\UnsubcribeConfirmation;
use App\Models\Access;
use App\Models\Unsubscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomePageController extends Controller
{
    public function __invoke()
    {
        return view('compte.index');
    }
}

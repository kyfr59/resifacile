<?php

namespace App\Http\Controllers\SaleProcess;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @return View
     */
    public function __invoke(): View
    {
        return view('sale-process.letter-payment');
    }
}

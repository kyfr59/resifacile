<?php

namespace App\Http\Controllers\SaleProcess;

use App\Http\Controllers\Controller;

class ValidationController extends Controller
{
    public function __invoke()
    {
        return view('sale-process.letter-validation');
    }
}

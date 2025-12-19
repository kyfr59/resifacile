<?php

namespace App\Http\Controllers\SaleProcess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __invoke()
    {
        return view('sale-process.letter-import');
    }
}

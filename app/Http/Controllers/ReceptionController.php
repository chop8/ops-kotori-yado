<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ReceptionController extends Controller
{
    public function index(): View
    {
        return view('reception.index');
    }
}

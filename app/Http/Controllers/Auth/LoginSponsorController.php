<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginSponsorController extends Controller
{
    public function showSponsorPage(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('auth.loginsponsor');
    }
}

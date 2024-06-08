<?php

namespace App\Http\Controllers;

use App\Models\Maker;
use Illuminate\Http\Request;

class MakerController extends Controller
{
    public function index()
    {
        $makers = Maker::all();
        return view('secretgarden.makers.index', compact('makers'));
    }
}



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobDeskController extends Controller
{
    public function index()
    {
        return view('main/index');
    }
}

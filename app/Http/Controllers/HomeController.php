<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // El helper view() busca en resources/views/. La notación de punto separa carpetas.
        return view('Home.home');
    }
}

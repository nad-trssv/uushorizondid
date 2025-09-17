<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('main.index');
    }

    public function index()
    {
        return view('main.home');
    }
    
    public function blog()
    {
        return view('main.blog');
    }
}

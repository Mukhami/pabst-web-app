<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $title = "Dashboard";
        return view('backend.index', compact('title'));
    }
}

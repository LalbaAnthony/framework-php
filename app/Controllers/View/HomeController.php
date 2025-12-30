<?php

namespace App\Controllers\View;

use App\Http\Request;
use App\Models\Post;
use App\Http\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $this->view('home');
    }
}

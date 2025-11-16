<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Models\Post;
use App\Controller\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $this->view('home');
    }
}

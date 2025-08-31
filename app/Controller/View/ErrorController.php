<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Controller\Controller;

class ErrorController  extends Controller
{
    public function error404(Request $request)
    {
        $this->view('404');
    }

    public function error500(Request $request)
    {
        $this->view('500');
    }
}

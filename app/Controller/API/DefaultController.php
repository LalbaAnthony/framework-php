<?php

namespace App\Controller\API;

use App\Http\Request;
use App\Controller\Controller;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $this->json(['message' => 'This is the API root endpoint.'], 200);
    }
}

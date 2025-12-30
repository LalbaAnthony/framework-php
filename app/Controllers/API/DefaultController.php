<?php

namespace App\Controllers\API;

use App\Http\Request;
use App\Http\Controller;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $this->json(['message' => 'This is the API root endpoint.'], 200);
    }
}

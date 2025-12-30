<?php

namespace App\Controllers\View;

use App\Http\Request;
use App\Http\Controller;

class ErrorController extends Controller
{
    public function error(Request $request, mixed $data = null)
    {
        $code = $data['code'] ?? 500;
        $message = $data['message'] ?? 'An error occurred';

        $this->view('error', [
            'code' => (string) $code,
            'uri' => $request->uri,
            'message' => $message
        ], $code);
    }
}

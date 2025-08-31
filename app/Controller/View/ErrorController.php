<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Controller\Controller;

class ErrorController  extends Controller
{
    public function error401(Request $request)
    {
        $this->view('error', [
            'code' => '401',
            'uri' => $request->uri,
            'message' => 'Non autorisé &#x1F6AB;'
        ], 401);
    }

    public function error403(Request $request)
    {
        $this->view('error', [
            'code' => '403',
            'uri' => $request->uri,
            'message' => 'Accès interdit &#x1F6AB;'
        ], 403);
    }

    public function error404(Request $request)
    {
        $this->view('error', [
            'code' => '404',
            'uri' => $request->uri,
            'message' => 'Oups! Il semblerait que la page que tu cherche n\'existe pas &#x1F614;'
        ], 404);
    }

    public function error500(Request $request)
    {
        $this->view('error', [
            'code' => '500',
            'uri' => $request->uri,
            'message' => 'La c\'est la douille ... Une erreur est survenue sur le serveur &#x1F62D;'
        ], 500);
    }
}

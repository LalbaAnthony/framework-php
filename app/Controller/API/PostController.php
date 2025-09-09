<?php

namespace App\Controller\API;

use App\Http\Request;
use App\Models\Post;
use App\Controller\Controller;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = (string) ($request->params['search'] ?? '');
        $perPage = (int) ($request->params['perPage'] ?? parent::DEFAULT_PER_PAGE);
        $page = (int) ($request->params['page'] ?? parent::DEFAULT_PAGE);
        $sort = (array) ($request->params['sort'] ?? parent::DEFAULT_SORT);

        $return = Post::findAllBy([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $status = count((array) $return['data']) > 0 ? 200 : 204;

        $this->json($return, $status);
    }
}

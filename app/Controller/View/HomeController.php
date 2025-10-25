<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Models\Post;
use App\Controller\Controller;

class HomeController extends Controller
{
    const DEFAULT_SORT = [['column' => 'date', 'order' => 'DESC']];

    public function index(Request $request)
    {
        $search = (string) ($request->params['search'] ?? '');
        $perPage = (int) ($request->params['perPage'] ?? self::DEFAULT_PER_PAGE);
        $page = (int) ($request->params['page'] ?? parent::DEFAULT_PAGE);
        $sort = (array) ($request->params['sort'] ?? parent::DEFAULT_SORT);

        $posts = Post::findAllBy([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $this->view('home', compact('posts', 'search', 'perPage', 'page', 'sort'));
    }
}

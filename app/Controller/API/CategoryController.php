<?php

namespace App\Controller\API;

use App\Http\Request;
use App\Models\Category;
use App\Controller\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = (string) ($request->params['search'] ?? '');
        $per = (int) ($request->params['per'] ?? parent::DEFAULT_PER_PAGE);
        $page = (int) ($request->params['page'] ?? parent::DEFAULT_PAGE);
        $sort = (array) ($request->params['sort'] ?? parent::DEFAULT_SORT);

        $return = Category::findAllBy([
            'search' => $search,
            'per' => $per,
            'page' => $page,
            'sort' => $sort,
        ]);

        $status = count((array) $return['data']) > 0 ? 200 : 204;

        $this->json($return, $status);
    }
}

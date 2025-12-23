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

        [$data, $meta] = Category::findAllBy([
            'search' => $search,
            'per' => $per,
            'page' => $page,
            'sort' => $sort,
        ]);

        $status = count((array) $data) > 0 ? 200 : 204;

        $this->json(['data' => $data, 'meta' => $meta], $status);
    }

    public function show(Request $request)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        if ($id <= 0) {
            $this->json(['error' => 'Invalid category ID'], 400);
            return;
        }

        $category = Category::findOne($id);

        if (!$category) {
            $this->json(['error' => 'Category not found'], 404);
            return;
        }

        $this->json(['data' => [$category]], 200);
    }
}

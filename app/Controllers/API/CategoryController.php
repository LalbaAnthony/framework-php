<?php

namespace App\Controllers\API;

use App\Http\Request;
use App\Models\Category;
use App\Http\Controller;
use App\Database\Model;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = (string) ($request->params['search'] ?? '');
        $perPage = (int) ($request->params['perPage'] ?? parent::DEFAULT_PER_PAGE);
        $page = (int) ($request->params['page'] ?? parent::DEFAULT_PAGE);
        $sort = (array) ($request->params['sort'] ?? parent::DEFAULT_SORT);

        [$data, $meta] = Category::findAll([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $data = Model::parseArraySafe($data);

        $status = count((array) $data) > 0 ? 200 : 204;

        $this->json(['data' => $data, 'meta' => $meta], $status);
    }

    public function show(Request $request)
    {
        $slug = (string) ($request->patterns['slug'] ?? '');

        $category = Category::findByCol($slug, 'slug');

        if (!$category) {
            $this->json(['error' => 'Category not found'], 404);
            return;
        }

        $category = $category->toArraySafe();

        $this->json(['data' => [$category]], 200);
    }
}

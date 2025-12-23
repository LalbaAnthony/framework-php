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

        [$data, $meta] = Post::findAllBy([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $status = count((array) $data) > 0 ? 200 : 204;

        $this->json(['data' => $data, 'meta' => $meta], $status);
    }

    public function show(Request $request, mixed $data = null)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        if ($id <= 0) {
            $this->json(['error' => 'Invalid post ID'], 400);
            return;
        }

        $post = Post::findOne($id);

        if (!$post) {
            $this->json(['error' => 'Post not found'], 404);
            return;
        }

        $this->json(['data' => [$post]], 200);
    }
}

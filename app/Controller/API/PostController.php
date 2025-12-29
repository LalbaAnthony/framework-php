<?php

namespace App\Controller\API;

use App\Http\Request;
use App\Models\Post;
use App\Controller\Controller;
use App\Models\Model;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = (string) ($request->params['search'] ?? '');
        $perPage = (int) ($request->params['perPage'] ?? parent::DEFAULT_PER_PAGE);
        $page = (int) ($request->params['page'] ?? parent::DEFAULT_PAGE);
        $sort = (array) ($request->params['sort'] ?? parent::DEFAULT_SORT);

        [$data, $meta] = Post::findAll([
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

        $post = Post::findByCol('slug', $slug);

        $post = $post->toArraySafe();

        if (!$post) {
            $this->json(['error' => 'Post not found'], 404);
            return;
        }

        $this->json(['data' => [$post]], 200);
    }
}

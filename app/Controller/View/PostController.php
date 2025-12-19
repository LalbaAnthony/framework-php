<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Models\Post;
use App\Controller\Controller;
use App\Validator;

class PostController extends Controller
{
    const DEFAULT_SORT = [['column' => 'date', 'order' => 'DESC']];

    public function index(Request $request)
    {
        $search = (string) ($request->params['search'] ?? '');
        $per = (int) ($request->params['per'] ?? self::DEFAULT_PER_PAGE);
        $page = (int) ($request->params['page'] ?? parent::DEFAULT_PAGE);
        $sort = (array) ($request->params['sort'] ?? parent::DEFAULT_SORT);

        $posts = Post::findAllBy([
            'search' => $search,
            'per' => $per,
            'page' => $page,
            'sort' => $sort,
        ]);

        $this->view('post/list', compact('posts', 'search', 'sort'));
    }

    public function show(Request $request)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        $post = Post::findOne($id);

        if (!$post) {
            $this->view('error');
            return;
        }

        $this->view('post/detail', compact('post'));
    }

    public function update(Request $request)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        $post = Post::findOne($id);

        if (!$post) {
            $this->view('error');
            return;
        }

        $validator = Validator::make($request->all(), [
            "slug" => "required",
            "title" => "required",
            "content" => "required",
            "date" => "required|date",
            "published" => "boolean",
        ]);
        var_dump($validator->errors);

        // TODO: Validate input data
        // TODO: Save the post
        // TODO: Handle errors
        // TODO: Redirect or show success message

        $this->view('post/detail', compact('post'));
    }
}

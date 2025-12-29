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
        $perPage = (int) ($request->params['perPage'] ?? self::DEFAULT_PER_PAGE);
        $page = (int) ($request->params['page'] ?? parent::DEFAULT_PAGE);
        $sort = (array) ($request->params['sort'] ?? parent::DEFAULT_SORT);

        [$posts, $meta] = Post::findAll([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $this->view('post/list', compact('posts', 'meta', 'search', 'sort'));
    }

    public function show(Request $request)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        $post = Post::findByPk($id);

        if (!$post) {
            $this->view('error', ['code' => 404, 'message' => 'Post not found']);
            return;
        }

        $this->view('post/detail', compact('post'));
    }

    public function update(Request $request)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        $post = Post::findByPk($id);

        if (!$post) {
            $this->view('error', ['code' => 404, 'message' => 'Post not found']);
            return;
        }

        $validator = Validator::make($request->all(), [
            "slug" => "required|slug|unique:post,slug,{$post->id}",
            "title" => "required",
            "content" => "required",
            "date" => "required|date",
            "published" => "boolean",
        ]);

        $post->slug = $request->body['slug'];
        $post->title = $request->body['title'];
        $post->content = $request->body['content'];
        $post->date = $request->body['date'];
        $post->published = $request->body['published'] ?? false;

        if ($validator->hasErrors()) {
            $this->view('post/detail', ['post' => $post, 'errors' => $validator->errors]);
            return;
        }

        $post->save();

        $this->view('post/detail', ['post' => $post, 'success' => 'Post updated successfully.']);
    }
}

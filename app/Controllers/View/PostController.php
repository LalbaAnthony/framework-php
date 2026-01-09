<?php

namespace App\Controllers\View;

use App\Http\Request;
use App\Models\Post;
use App\Http\Controller;
use App\Models\Category;
use App\Util\Validator;

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

        $success = null;
        if (isset($request->params['deleted'])) $success = 'Post deleted successfully.';
        if (isset($request->params['updated'])) $success = 'Post updated successfully.';

        $this->view('post/list', compact('posts', 'meta', 'search', 'sort', 'success'));
    }

    public function show(Request $request)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        $post = Post::findByPk($id);
        $categories = Category::findAll(['perPage' => 1000])[0];

        if (!$post) {
            $this->view('error', ['code' => 404, 'message' => 'Post not found']);
            return;
        }

        $this->view('post/detail', compact('post', 'categories'));
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
        $post->published = isset($request->body['published']) ? true : false;

        if ($validator->hasErrors()) {
            $this->view('post/detail', ['post' => $post, 'errors' => $validator->errors]);
            return;
        }

        $post->saveCategories($request->body['categories'] ?? []);
        $post->save();

        $this->redirect('/posts', ['updated' => true]);
    }

    public function delete(Request $request)
    {
        $id = (int) ($request->patterns['id'] ?? 0);

        $post = Post::findByPk($id);

        if (!$post) {
            $this->view('error', ['code' => 404, 'message' => 'Post not found']);
            return;
        }

        $post->delete();

        $this->redirect('/posts', ['deleted' => true]);
    }
}

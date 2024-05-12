<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PostController extends Controller
{

    protected $sortColumn = 'created_at';
    protected $sortBy = 'asc';
    protected $q = '';
    protected $limit = 10;
    protected $page = 1;
    protected $sortParams = ['asc', 'desc'];
    public function index(Request $request)
    {
        // $post = Post::factory(5)->create();
        // return $post;
        $query = Post::query();
        if ($request->has('page') && is_numeric($request->input('page'))) $this->page = $request->has('page');
        if ($request->has('sortBy') && in_array($request->input('sortBy'), $this->sortParams)) $this->sortBy = $request->input('sortBy');
        if ($request->has('limit') && is_numeric($request->input('limit'))) $this->limit = $request->input('limit');
        if ($request->has('query')) {
            $q = $request->input('query');
            $query->where('title', 'LIKE', '%' . $q . '%')
                ->orWhere('metaTitle', 'LIKE', '%' . $q . '%');
        }

        $query->orderBy($this->sortColumn, $this->sortBy)->paginate((int)$this->limit, ['*'], 'page', (int)$this->page);
        $query->with(['categories', 'tags', 'user']);
        $posts = $query->get();

        return response()->json($posts);
    }
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->user = $post->user;
            return $post;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Post not found.'
            ], 404);
        }
    }
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        try {
            $user = User::findOrFail($validated['authorId']);
            $post = new Post();
            $post->authorId = $user->id;
            $post->title = $validated['title'];
            $post->metaTitle = $validated['metaTitle'];
            $post->slug = $validated['slug'];
            if (is_null($post->slug)) $post->slug = Str::slug($post->title);
            $post->content = $validated['content'];
            $post->summary = $validated['summary'];
            $post->published = $validated['published'];
            if ($validated['tag'] != '') {
                //check tag and add
            }
            // $post->save();
            // return $post;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'User not found.'
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);
            return $post;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Post not found.'
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            if (Post::findOrFail($id)->delete()) {
                return response()->json([
                    'error' => 0,
                    'message' => 'Successful.'
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Post not found.'
            ], 404);
        }
    }
}

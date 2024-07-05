<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Jobs\DeleteTag;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostMeta;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function index(IndexPostRequest $request)
    {
        $query = Post::query();
        if ($request->has('page')) $this->page = $request->has('page');
        if ($request->has('sortBy')) $this->sortBy = $request->input('sortBy');
        if ($request->has('limit')) $this->limit = $request->input('limit');
        if ($request->has('query')) {
            $q = $request->input('query');
            $query->where('title', 'LIKE', '%' . $q . '%')
                ->orWhere('metaTitle', 'LIKE', '%' . $q . '%');
        }

        $posts = $query->orderBy($this->sortColumn, $this->sortBy)->paginate((int)$this->limit, ['*'], 'page', (int)$this->page);
        return new PostCollection($posts);
    }
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->user = $post->user;
            return new PostResource($post);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Post not found.'
            ], 404);
        }
    }
    public function store(StorePostRequest $request)
    {
        $data = DB::transaction(function () use ($request) {
            $user = User::findOrFail($request['authorId']);
            $post = new Post();
            $post->authorId  = $user->id;
            $post->title     = $request['title'];
            $post->metaTitle = $request['metaTitle'];
            $post->slug      = $request['slug'];
            if (is_null($post->slug)) $post->slug = Str::slug($post->title);
            $post->content   = $request['content'];
            $post->summary   = $request['summary'];
            $post->published = $request['published'] ?? 0;
            $post->createdAt = Carbon::now();
            $post->updatedAt = Carbon::now();

            $post->save();
            if ($request['tags']) {
                //check tag and add
                foreach ($request['tags'] as $tagTitle) {
                    $tagObj = Tag::where('title', $tagTitle)->first();
                    $tagId = $tagObj ? (int)$tagObj->id : false;
                    if (!$tagId) {
                        $tagObj = new Tag();
                        $tagObj->title = $tagTitle;
                        $tagObj->metaTitle = $tagTitle;
                        $tagObj->slug  = Str::slug($tagTitle);
                        $tagObj->content = "";
                        if ($tagObj->save())
                            $tagId = (int)$tagObj->id;
                    }
                    PostTag::create([
                        'postId' => $post->id,
                        'tagId' => $tagId,
                    ]);
                }
            }
            //check category and add
            if ($request['categories']) {
                foreach ($request['categories'] as $categoryTitle) {
                    $categoryObj = Category::where('title', $categoryTitle)->first();
                    $categoryId = $categoryObj ? (int)$categoryObj->id : false;
                    if (!$categoryId) {
                        $categoryObj = new Category();
                        $categoryObj->title = $categoryTitle;
                        $categoryObj->metaTitle = $categoryTitle;
                        $categoryObj->slug  = Str::slug($categoryTitle);
                        $categoryObj->content = "";
                        if ($categoryObj->save())
                            $categoryId = (int)$categoryObj->id;
                    }
                    PostCategory::create([
                        'postId' => $post->id,
                        'categoryId' => $categoryId,
                    ]);
                }
            }
            return new PostResource($post);
        });

        return $data;
    }
    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $post = Post::findOrFail($id);
            $data = DB::transaction(function () use ($request, $post) {
                $user = User::findOrFail($request['authorId']);
                $post->authorId  = $user->id;
                $post->title     = $request['title'];
                $post->metaTitle = $request['metaTitle'];
                $post->slug      = $request['slug'];
                if (is_null($post->slug)) $post->slug = Str::slug($post->title);
                $post->content   = $request['content'];
                $post->summary   = $request['summary'];
                $post->published = $request['published'] ?? 0;
                $post->createdAt = Carbon::now();
                $post->updatedAt = Carbon::now();
                $post->update();

                if ($request['tags']) {
                    //check tag and add
                    foreach ($request['tags'] as $tagTitle) {
                        $tagObj = Tag::where('title', $tagTitle)->first();
                        $tagId = $tagObj ? (int)$tagObj->id : false;
                        if (!$tagId) {
                            $tagObj = new Tag();
                            $tagObj->title = $tagTitle;
                            $tagObj->metaTitle = $tagTitle;
                            $tagObj->slug  = Str::slug($tagTitle);
                            $tagObj->content = "";
                            if ($tagObj->save())
                                $tagId = (int)$tagObj->id;
                        }
                        PostTag::create([
                            'postId' => $post->id,
                            'tagId' => $tagId,
                        ]);
                    }
                }

                //check category and add
                if ($request['categories']) {
                    foreach ($request['categories'] as $categoryTitle) {
                        $categoryObj = Category::where('title', $categoryTitle)->first();
                        $categoryId = $categoryObj ? (int)$categoryObj->id : false;
                        if (!$categoryId) {
                            $categoryObj = new Category();
                            $categoryObj->title = $categoryTitle;
                            $categoryObj->metaTitle = $categoryTitle;
                            $categoryObj->slug  = Str::slug($categoryTitle);
                            $categoryObj->content = "";
                            if ($categoryObj->save())
                                $categoryId = (int)$categoryObj->id;
                        }
                        PostCategory::create([
                            'postId' => $post->id,
                            'categoryId' => $categoryId,
                        ]);
                    }
                }
                return new PostResource($post);
            });
            return $data;
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
            $post = Post::findOrFail($id);
            if (count($post->tags) > 0)   DeleteTag::dispatch($post->tags)->delay(now()->addMinutes(2));
            DB::beginTransaction();
            PostTag::where('postId', '=', (int)$id)->delete();
            PostCategory::where('postId', '=', (int)$id)->delete();
            $post->delete();
            DB::commit();

            return response()->json([
                'error' => 0,
                'message' => 'Successful.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Post not found.'
            ], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $sortColumn = 'created_at';
    protected $sortBy = 'asc';
    protected $q = '';
    protected $limit = 10;
    protected $page = 1;
    protected $sortParams = ['asc', 'desc'];
    public function index(Request $request)
    {
        if ($request->has('page') && is_numeric($request->input('page'))) $this->page = $request->input('page');
        if ($request->has('sortBy') && in_array($request->input('sortBy'), $this->sortParams)) $this->sortBy = $request->input('sortBy');
        if ($request->has('limit') && is_numeric($request->input('limit'))) $this->limit = $request->input('limit');

        if (
            !count($request->query()) || (
                $this->sortColumn == 'created_at' &&
                $this->sortBy == 'asc' &&
                $this->q == '' &&
                $this->limit == 10 &&
                $this->page == 1
            )
        ) {

            if (Cache::has('tags')) {

                Log::info('Read Tags from cache');
                $tagsCache = Cache::get('tags');
                return new TagCollection($tagsCache);
            } else {

                Log::info('Save Tags cache');
                // dd('vao day 11');
                $tagsCache = Cache::rememberForever('tags',  function () {
                    return Tag::query()->orderBy($this->sortColumn, $this->sortBy)->paginate((int)$this->limit, ['*'], 'page', (int)$this->page);
                });
                return new TagCollection($tagsCache);
            }
        } else {

            Log::info('Get tags live');
            $query = Tag::query();
            if ($request->has('query')) {
                $q = $request->input('query');
                $query->where('title', 'LIKE', '%' . $q . '%')
                    ->orWhere('metaTitle', 'LIKE', '%' . $q . '%');
            }
            $tags = $query->orderBy($this->sortColumn, $this->sortBy)->paginate((int)$this->limit, ['*'], 'page', (int)$this->page);
            return new TagCollection($tags);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $tag  = new Tag();
        $tag->title     = $request['title'];
        $tag->metaTitle = $request['metaTitle'];
        $tag->slug      = $request['slug'];
        if (is_null($tag->slug)) $tag->slug = Str::slug($tag->title);
        $tag->content   = $request['content'] ?? '';
        $tag->save();
        return new TagResource($tag);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $tag = Tag::findOrFail($id);
            return $tag;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Tag not found.'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

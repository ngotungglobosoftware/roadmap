<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        //
        $query = Tag::query();
        // dd($request->query());
        if ($request->has('page') && is_numeric($request->input('page'))) $this->page = $request->has('page');
        if ($request->has('sortBy') && in_array($request->input('sortBy'), $this->sortParams)) $this->sortBy = $request->input('sortBy');
        if ($request->has('limit') && is_numeric($request->input('limit'))) $this->limit = $request->input('limit');
        if ($request->has('query')) {
            $q = $request->input('query');
            $query->where('title', 'LIKE', '%' . $q . '%')
                ->orWhere('metaTitle', 'LIKE', '%' . $q . '%');
        }
        $tags = $query->orderBy($this->sortColumn, $this->sortBy)->paginate((int)$this->limit, ['*'], 'page', (int)$this->page);
        return new TagCollection($tags);
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
        try {
            if (Tag::findOrFail($id)->delete()) {
                return response()->json([
                    'error' => 0,
                    'message' => 'Successful.'
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Tag not found.'
            ], 404);
        }
    }
}

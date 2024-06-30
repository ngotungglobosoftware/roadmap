<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
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
    protected $startTime = null;
    protected $endTime = null;

    public function index(Request $request)
    {
        //
        $query = Category::query();
        if ($request->has('page')) $this->page = $request->has('page');
        if ($request->has('sortBy')) $this->sortBy = $request->input('sortBy');
        if ($request->has('limit')) $this->limit = $request->input('limit');
        if ($request->has('startTime')) $this->startTime =  $request->input('startTime');
        if ($request->has('endTime')) $this->endTime =  $request->input('endTime');
        if ($request->has('query')) {
            $q = $request->input('query');
            $query->where('title', 'LIKE', '%' . $q . '%')
                ->orWhere('metaTitle', 'LIKE', '%' . $q . '%');
        }
        if ($this->startTime && $this->endTime) {
            $this->startTime = Carbon::parse($this->startTime)->format('Y-m-d H:i:s');
            $this->endTime = Carbon::parse($this->endTime)->format('Y-m-d H:i:s');
            $query->where('created_at', '>=', $this->startTime)
                ->where('created_at', '<=', $this->endTime);

            // $sql = $query->toSql();
            // Get the bindings
            // $bindings = $query->getBindings();

            // Replace the placeholders with actual bindings
            // $fullSql = Str::replaceArray('?', $bindings, $sql);
            // Print the full SQL query with actual values
            // dd($fullSql);
            // print_r($this->startTime);
            // print_r($this->endTime);
            // dd($query->toSql());
        }
        $categories = $query->orderBy($this->sortColumn, $this->sortBy)->paginate((int)$this->limit, ['*'], 'page', (int)$this->page);
        return new CategoryCollection($categories);
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
    public function store(StoreCategoryRequest $request)
    {
        //
        $category = new Category();
        $category->title     = $request['title'];
        $category->metaTitle = $request['metaTitle'];
        $category->slug      = $request['slug'];
        if (is_null($category->slug)) $category->slug = Str::slug($category->title);
        $category->content   = $request['content'] ?? '';
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $category = Category::findOrFail($id);
            return $category;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Category not found.'
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
    public function update(UpdateCategoryRequest $request, string $id)
    {
        //
        try {
            $category = Category::findOrFail($id);
            $category->title     = $request['title'];
            $category->metaTitle = $request['metaTitle'];
            $category->slug      = $request['slug'];
            if (is_null($category->slug)) $category->slug = Str::slug($category->title);
            $category->content   = $request['content'] ?? '';
            $category->update();
            return  new CategoryResource($category);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Category not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            if (Category::findOrFail($id)->delete()) {
                return response()->json([
                    'error' => 0,
                    'message' => 'Successful.'
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Category not found.'
            ], 404);
        }
    }
}

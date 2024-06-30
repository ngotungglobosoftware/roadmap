<?php

namespace App\Http\Controllers;

use App\Filters\ApiFilter;
use App\Http\Requests\ListUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ResourceUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    protected $sortColumn = 'created_at';
    protected $sortBy = 'asc';
    protected $q = '';
    protected $limit = 10;
    protected $page = 1;
    protected $sortParams = ['asc', 'desc'];


    public function index(Request $request)
    {

        $query = User::query();
        if ($request->has('page') && is_numeric($request->input('page'))) $this->page = $request->has('page');
        if ($request->has('query')) {
            $q = $request->input('query');
            $query->where('firstName', 'LIKE', '%' . $q . '%')
                ->orWhere('middleName', 'LIKE', '%' . $q . '%')
                ->orWhere('middleName', 'LIKE', '%' . $q . '%')
                ->orWhere('email', 'LIKE', '%' . $q . '%');
        }
        $users = $query->orderBy($this->sortColumn, $this->sortBy)->paginate((int)$this->limit, ['*'], 'page', (int)$this->page);
        return UserResource::collection($users);
    }
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $user;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'User not found.'
            ], 404);
        }
    }
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $user = new User();
        $user->firstName = $validated['first_name'];
        $user->middleName = $validated['middle_name'];
        $user->lastName = $validated['last_name'];
        $user->mobile = $validated['phone'];
        $user->email = $validated['email'];
        $user->passwordHash = Hash::make($validated['password']);
        $user->intro = $validated['intro'] ?? "";
        $user->profile = $validated['profile'] ?? "";
        $user->save();
        return $user;
    }
    public function update(UpdateUserRequest $request, $id)
    {

        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();
            $user->firstName = $validated['first_name'];
            $user->middleName = $validated['middle_name'];
            $user->lastName = $validated['last_name'];
            $user->mobile = $validated['phone'];
            $user->passwordHash = Hash::make($validated['password']);
            $user->intro = $validated['intro'] ?? "";
            $user->profile = $validated['profile'] ?? "";
            $user->update();
            return $user;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'User not found.'
            ], 404);
        }
    }
    public function destroy($id)
    {
        try {
            if (User::findOrFail($id)->delete()) {
                return response()->json([
                    'error' => 0,
                    'message' => 'Successful.'
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'User not found.'
            ], 404);
        }
    }
}

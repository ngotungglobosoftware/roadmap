<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $limit = 1;
        $page = 2;
        $orderBy = "asc";
        $users = Post::orderBy('created_at', $orderBy)->paginate($limit, ['*'], 'page', $request->page);
        return $users;
    }
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }
    public function store(Request $request)
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
    public function update(Request $request, $id)
    {
        $validated = $request->validated();
        $user = User::find($id);
        $user->firstName = $validated['first_name'];
        $user->middleName = $validated['middle_name'];
        $user->lastName = $validated['last_name'];
        $user->mobile = $validated['phone'];
        $user->passwordHash = Hash::make($validated['password']);
        $user->intro = $validated['intro'] ?? "";
        $user->profile = $validated['profile'] ?? "";
        $user->update();
        return $user;
    }
    public function destroy($id)
    {
        if (User::find($id)->delete()) {
            return "Successful";
        }
        return "Failed";
    }
}

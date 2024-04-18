<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User as ResourcesUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $limit = 1;
        $page = 2;
        $orderBy = "asc";
        $users = User::orderBy('created_at', $orderBy)->paginate($limit, ['*'], 'page', $request->page);
        return $users;
    }
    public function show($id)
    {
        $user = User::find($id);
        return $user;
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

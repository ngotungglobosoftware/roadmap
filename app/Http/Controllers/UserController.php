<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListUserRequest;
use App\Http\Requests\StoreUserRequest;
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
        $users = User::orderBy('created_at', $orderBy)->paginate($limit, ['*'], 'page', $page);
        return $users;
    }
    public function show(Request $request)
    {
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
        // $user->save();

        // print_r('<pre>');
        // print_r(compact('customer'));
        // die('sdfdsfdsf');
        Auth::guard('customer')->login($user);

        return redirect()->route('/');
    }
    public function update(Request $request)
    {
    }
    public function destroy(Request $request)
    {
    }
}

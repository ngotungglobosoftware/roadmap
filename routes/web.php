<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('front.home');
});
Route::get('/login', function () {
    return view('front.login');
});
Route::get('/signup', function () {
    return view('front.signup');
});
Route::post('/signup', function () {
    return view('front.signup');
});
// Route::post('/user', [UserController::class, 'store']);

Route::prefix('admin')->group(function () {
    Route::get('{any}', function() {
        return view('admin.index');
    });
    Route::get('{any}/{id}', function() {
        return view('admin.index');
    });
    Route::get('/', function() {
        return view('admin.index');
    });
});
Route::prefix('api')->group(function () {
    // Route::get('/users', [UserController::class, 'list']);
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
});
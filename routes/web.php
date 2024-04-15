<?php

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
Route::post('/user', [UserController::class, 'store']);

Route::prefix('admin')->group(function () {
    Route::get('{any}', function() {
        return view('admin.index');
    });
   
});
Route::get('/api/{any}', function () {
    die('api');
});

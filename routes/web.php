<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/admin', function () {
    return view('admin.index');
});
Route::get('/', function () {
    return view('front.home');
});
Route::get('/login', function () {
    return view('front.login');
});
Route::get('/signup', function () {
    return view('front.signup');
});
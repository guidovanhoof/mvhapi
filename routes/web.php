<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    function () {
        return view('home');
    }
);
Route::get(
    'home',
    function () {
        return view('home');
    }
);

Route::get(
    'login', 'App\Http\Controllers\Auth\LoginController@showLoginForm'
)
    ->name('login');

Route::post(
    'login',
    'App\Http\Controllers\Auth\LoginController@login'
);
Route::post(
    'logout',
    'App\Http\Controllers\Auth\LoginController@logout'
);

//Auth::routes();

Route::fallback(
    function () {
        return view('home');
    }
);

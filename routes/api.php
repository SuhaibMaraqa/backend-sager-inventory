<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/user/register', 'AuthController@register');
// Route::get('/user/register', 'AuthController@index');
// Route::post('/user/login', 'AuthController@login');
// Route::get('/user/me', 'AuthController@me');


Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', 'AuthController@user');
    Route::post('logout', 'AuthController@logout');
});

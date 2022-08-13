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

    Route::post('drone-model/add', 'AdminController@addDroneModel');
    Route::put('drone-model/{id}/update', 'AdminController@updateDroneModel');
    Route::delete('drone-model/{id}/delete', 'AdminController@deleteDroneModel');

    Route::post('payload-model/add', 'AdminController@addPayloadModel');
    Route::put('payload-model/{id}/update', 'AdminController@updatePayloadModel');
    Route::delete('payload-model/{id}/delete', 'AdminController@deletePayloadModel');

    Route::post('battery-model/add', 'AdminController@addBatteryModel');
    Route::put('battery-model/{id}/update', 'AdminController@updateBatteryModel');
    Route::delete('battery-model/{id}/delete', 'AdminController@deleteBatteryModel');

    Route::get('/home', 'HomeController@index');
});

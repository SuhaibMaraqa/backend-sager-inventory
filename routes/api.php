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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', 'AuthController@user');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');
    Route::get('users', 'HomeController@users');

    //Admin Routes
    Route::get('drone-models', 'AdminController@listDroneModels');
    Route::post('drone-model/add', 'AdminController@addDroneModel');
    Route::put('drone-model/{id}/update', 'AdminController@updateDroneModel');
    Route::delete('drone-model/{id}/delete', 'AdminController@deleteDroneModel');

    Route::get('payload-models', 'AdminController@listPayloadModels');
    Route::post('payload-model/add', 'AdminController@addPayloadModel');
    Route::put('payload-model/{id}/update', 'AdminController@updatePayloadModel');
    Route::delete('payload-model/{id}/delete', 'AdminController@deletePayloadModel');

    Route::get('battery-models', 'AdminController@listBatteryModels');
    Route::post('battery-model/add', 'AdminController@addBatteryModel');
    Route::put('battery-model/{id}/update', 'AdminController@updateBatteryModel');
    Route::delete('battery-model/{id}/delete', 'AdminController@deleteBatteryModel');

    //Center & Pilots routes
    Route::get('drones', 'InventoryController@indexDrone');
    Route::get('drone/{id}', 'InventoryController@showDrone');
    Route::post('drone/add', 'InventoryController@addDrone');
    Route::put('drone/{id}/update', 'InventoryController@updateDrone');
    Route::delete('drone/{id}/delete', 'InventoryController@deleteDrone');

    Route::get('payloads', 'InventoryController@indexPayload');
    Route::get('payload/{id}', 'InventoryController@showPayload');
    Route::post('payload/add', 'InventoryController@addPayload');
    Route::put('payload/{id}/update', 'InventoryController@updatePayload');
    Route::delete('payload/{id}/delete', 'InventoryController@deletePayload');

    Route::get('batteries', 'InventoryController@indexBatteries');
    Route::get('battery/{id}', 'InventoryController@showBattery');
    Route::post('battery/add', 'InventoryController@addBattery');
    Route::put('battery/{id}/update', 'InventoryController@updateBattery');
    Route::delete('battery/{id}/delete', 'InventoryController@deleteBattery');

    Route::get('book', 'BookingController@index');
    Route::post('book', 'BookingController@store');

    Route::get('/role', 'HomeController@index');
});

<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', 'LoginController@register');
Route::post('login', 'LoginController@signin');
Route::get('checkAuth', 'LoginController@checkAuth');

Route::post('uniqueEmail', 'LoginController@uniqueEmail');
Route::post('uniquePhone', 'LoginController@uniquePhone');

Route::get('inventory/getProducts', 'ProductController@getProducts');
Route::post('inventory/create', 'ProductController@store');
Route::post('inventory/update', 'ProductController@update');
Route::get('inventory/{id}', 'ProductController@show');
Route::delete('inventory/delete/{id}', 'ProductController@delete');

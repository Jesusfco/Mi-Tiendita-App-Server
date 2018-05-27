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


Route::post('sale', 'SaleController@storeSale');
Route::post('sale/outService', 'SaleController@storeSaleOutService');
Route::get('sales', 'SaleController@getSales');
Route::post('sales', 'SaleController@postSales');
Route::get('sales/{id}', 'SaleController@showSale');
Route::get('excel/sales', 'ExcelController@getSales');

Route::get('myUsers', 'UsersController@getUsers');
Route::post('myUsers', 'UsersController@create');
Route::post('myUsers/{id}', 'UsersController@edit');

Route::post('cash/globalCash', 'UsersController@updateGlobalCash');

Route::get('perfil', 'PerfilController@getPerfil');

Route::get('excel/inventory', 'ExcelController@getInventory');
Route::post('inventory/sync', 'ProductController@syncUp');
Route::post('cash/sync', 'UsersController@syncCash');
Route::get('checkConection', function(){
    return response()->json(true);
});
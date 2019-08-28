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

Route::group(['namespace' => 'Tenant\Api','domain' => '{subdomain}.' . config('app.url_base'),'middleware' => ['tenantConnection']], function () {
    Route::group(['prefix'=>'products'],function(){
        Route::get('','ProductController@index');
        Route::get('get-branch-products/{branch_id}','ProductController@getBranchProducts');
        Route::get('get-product-by-barcode/{barcode}', 'ProductController@getProductByBarcode');
    });
});
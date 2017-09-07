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
Route::group([], function () {
    Route::get('/user/{user}', 'Api\UserController@show');
    Route::put('/user/{user}', 'Api\UserController@update');
    Route::post('/user/{user}/notification', 'Api\UserController@storeNotification');
    Route::get('/articles', 'Api\ArticleController@all');
    Route::resource('/user/{user}/articles', 'Api\ArticleController');
});

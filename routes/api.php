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
    Route::post('/interim_user', 'Api\UserController@storeInterimUser');

    Route::get('/user/{api_token}', 'Api\UserController@showByApiToken')->where('api_token', '[a-zA-Z0-9]{60}');
    Route::get('/user/{user}', 'Api\UserController@show');
    Route::put('/user/{user}', 'Api\UserController@update');
    Route::put('/user/{user}/notification', 'Api\UserController@updateNotification');
    Route::put('/user/{user}/avator', 'Api\UserController@updateAvator');

    Route::get('/articles', 'Api\ArticleController@all');
    Route::get('/articles/{article}', 'Api\ArticleController@show');
    Route::post('/articles/{article}/like', 'Api\ArticleController@like');
    Route::resource('/user/{user}/articles', 'Api\ArticleController');
});

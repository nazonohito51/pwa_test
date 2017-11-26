<?php

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

use App\DataAccess\Eloquent\User;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user/{user}', function (User $user) {
    // TODO: make dashboard
    var_dump($user->articles);
});

Route::resource('/user/{user}/articles', 'ArticleController');
//Route::get('/app', 'FrontController@onsenVue')->name('app');
Route::get('/vue', 'FrontController@vue');
Route::get('/onsen', 'FrontController@onsen');

Route::group(['prefix' => 'app'], function () {
    Route::get('/', 'FrontController@onsenVue')->name('app');
    Route::fallback('FrontController@onsenVue');
});

Route::get('/images/avatars/{user}.png', function (User $user) {
    // Return image binary(png).
    // This is routing to make image uploading easy with Heroku.
    $png_binary = base64_decode(substr($user->avatar, strlen('data:image/png;base64,')));
    return response($png_binary, 200, [
        'Content-Type' => 'image/png'
    ]);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\CheckApiToken;
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
Route::group(['prefix' => 'v1','namespace'=>'Api'], function () {
    Route::get('/setting', 'SettingController@index')->middleware(CheckApiToken::class);

    Route::group(['prefix' => '/user'], function () {
        Route::post('/upload_attachment', 'UserController@upload_attachment')->middleware(CheckApiToken::class);
        Route::post('/remove_attachment', 'UserController@remove_attachment')->middleware(CheckApiToken::class);
        Route::post('/search', 'UserController@search');
        Route::post('/', 'UserController@register');
        Route::post('/login', 'UserController@login');
        Route::get('/types', 'UserController@types');
        Route::get('/{user_type_id}/list', 'UserController@users_list');
        Route::get('/logout', 'UserController@logout')->middleware(CheckApiToken::class);
        Route::post('/send_code', 'UserController@send_activation_code');
        Route::post('/activate', 'UserController@activate');
        Route::post('/update_password', 'UserController@update_password')->middleware(CheckApiToken::class);
        Route::get('/profile', 'UserController@profile')->middleware(CheckApiToken::class);
        Route::post('/{id}', 'UserController@update')->middleware(CheckApiToken::class);
    });
    Route::group(['prefix' => '/contact'], function () {
        Route::get('/types', 'ContactController@types')->middleware(CheckApiToken::class);
        Route::post('/', 'ContactController@store')->middleware(CheckApiToken::class);
    });
    Route::group(['prefix' => '/order'], function () {
        Route::get('/types', 'OrderController@types')->middleware(CheckApiToken::class);
        Route::post('/', 'OrderController@store')->middleware(CheckApiToken::class);
    });

});

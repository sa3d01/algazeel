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
    Route::get('/setting', 'SettingController@index');

    Route::group(['prefix' => '/user'], function () {
        Route::post('/', 'UserController@register');
        Route::post('/resend_code', 'UserController@resend_code');
        Route::post('/login', 'UserController@login');
        Route::group(['middleware' => CheckApiToken::class], function () {
            Route::post('/activate', 'UserController@activate');
            Route::post('/update', 'UserController@update');
            Route::get('/{id}', 'UserController@show');
        });
    });

});

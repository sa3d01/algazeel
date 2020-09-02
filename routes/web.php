<?php

use Illuminate\Support\Facades\Route;

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
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){
    Route::namespace('Auth')->group(function(){
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login')->name('login.submit');
        Route::post('/logout','LoginController@logout')->name('logout');
        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');
    });
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/setting', 'HomeController@setting')->name('setting');
    Route::post('/setting', 'HomeController@update_setting')->name('setting.update');

    Route::get('/admin/{id}', 'AdminController@show')->name('profile');
    Route::post('/admin/{id}', 'AdminController@update')->name('update');

    Route::post('user/{id}', 'UserController@update')->name('user.update');
    Route::resource('user', 'UserController');
    Route::get('user/activate/{id}', 'UserController@activate')->name('user.activate');

    Route::post('page/{id}', 'PageController@update')->name('page.update');
    Route::resource('page', 'PageController');
    Route::get('page/activate/{id}', 'PageController@activate')->name('page.activate');



});
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

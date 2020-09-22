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
//Route::prefix('/admin')->name('admin.')->middleware(['auth', 'permission:access-dashboard'])->namespace('Admin')->group(function(){
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

    Route::get('admin/profile', 'AdminController@profile')->name('profile');
    Route::post('admin/update_profile/{id}', 'AdminController@update_profile')->name('update_profile');
    Route::resource('admin', 'AdminController');
    Route::post('/admin/{id}', 'AdminController@update')->name('update');
    Route::get('admin/activate/{id}', 'AdminController@activate')->name('admin.activate');

    Route::resource('role', 'RoleController');
    Route::post('/role/{id}', 'RoleController@update')->name('update');

    Route::post('user/{id}', 'UserController@update')->name('user.update');
    Route::resource('user', 'UserController');
    Route::get('user/activate/{id}', 'UserController@activate')->name('user.activate');

    Route::post('provider/{id}', 'ProviderController@update')->name('provider.update');
    Route::resource('provider', 'ProviderController');
    Route::get('provider/activate/{id}', 'ProviderController@activate')->name('provider.activate');




});
Auth::routes();
Route::get('/', function (){
    return redirect()->route('admin.home');
});


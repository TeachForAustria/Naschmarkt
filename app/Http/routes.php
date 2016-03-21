<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Redirects the user to the about page
Route::get('/about', function () {
    return view('about');
});

//upload page
Route::get('/upload', function() {
    return view('upload');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

});

Route::group(['middleware' => 'web'], function () {
    Route::auth();


    Route::get('auth/socialLogin', 'Auth\AuthController@redirectToProvider');
    Route::get('auth/socialLogin/callback', 'Auth\AuthController@handleProviderCallback');
    Route::get('/home', 'HomeController@index');
});

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

//Redirects the user to the about page
Route::get('/about', function () {
    return view('about');
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

Route::group(['middleware' => 'web'], function () {
    // manually add auth routes since we don't want to allow user registration
    // Login/Logout
    Route::get('login', 'App\Http\Controllers\Auth\AuthController@showLoginForm');
    Route::post('login', 'App\Http\Controllers\Auth\AuthController@login');
    Route::get('logout', 'App\Http\Controllers\Auth\AuthController@logout');

    // Registration
    Route::get('register', 'App\Http\Controllers\Auth\AuthController@showRegistrationForm');
    Route::post('register', 'App\Http\Controllers\Auth\AuthController@register');

    // Password Reset
    Route::get('password/reset/{token?}', 'App\Http\Controllers\Auth\PasswordController@showResetForm');
    Route::post('password/email', 'App\Http\Controllers\Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'App\Http\Controllers\Auth\PasswordController@reset');


    Route::get('auth/socialLogin', 'Auth\AuthController@redirectToProvider');
    Route::get('auth/socialLogin/callback', 'Auth\AuthController@handleProviderCallback');

    // application routes
    // the auth middleware is being applied within the AppController anyway, so we don't need to add it here
    Route::get('/', 'AppController@index');

    //upload page
    Route::get('/upload', function() {
        return view('upload');
    });
});

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
    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');

    // Registration
    Route::get('register', 'Auth\AuthController@showRegistrationForm');
    Route::post('register', 'Auth\AuthController@register');

    // Password Reset
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    // Login/Connect via social networks
    Route::get('auth/socialLogin', 'Auth\AuthController@redirectToProvider');
    Route::get('auth/socialLogin/callback', 'Auth\AuthController@handleProviderCallback');

    // Disconnect User
    Route::get('/auth/socialLogin/disconnect', 'Auth\AuthController@disconnectSocialLogin');

    // Account Activation
    Route::get('activate', 'Auth\AuthController@showActivationForm');
    Route::post('activate', 'Auth\AuthController@postActivate');

    //
    // application routes
    //
    // the auth middleware is being applied within the AppController anyway, so we don't need to add it here

    // Redirect / to the wordcloud, can be changed though
    Route::get('/', 'AppController@cloud');

    //upload page
    Route::get('/upload', 'PostController@showUploadView');
    Route::post('/upload', 'PostController@uploadPost');


    //wordcloud page
    Route::get('/cloud', 'AppController@cloud');

    //search page
    Route::get('/search', 'SearchController@searchForQuery');
    Route::get('/search/{tag}', 'SearchController@searchForTag');

    //profile page
    Route::get('/user/{user}', 'AppController@profile');

    // posts
    Route::get('/posts', 'PostController@showPostsView');
    Route::get('/posts/deletePost/{post}', 'PostController@deletePost');

    // cloud controller
    Route::get('cloudController', 'CloudController@showTagsView');
    Route::get('/mostViewedTags', 'CloudController@filterMostViewed');

    // post views
    Route::get('/posts/{id}', 'PostController@showViewPostView');
    Route::post('/posts/{id}', 'PostController@update');
    Route::get('/posts/{id}/edit', 'PostController@showEditPostView');
    Route::get('/posts/download/{post}', 'PostController@getZipDownload');

    // documents
    Route::get('/documents/{id}', 'DocumentController@getFileDownload');

    // file upload backend
    Route::post('/documents', 'DocumentVersionController@uploadFile');

});

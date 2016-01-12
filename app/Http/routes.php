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






Route::resource('test','TestController');


Route::resource('test1','TestOneController');


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

    Route::get('admin/login', 'Admin\Auth\AuthController@getLogin');
    Route::post('admin/login', 'Admin\Auth\AuthController@postLogin');
    Route::get('admin/logout', 'Admin\Auth\AuthController@logout');
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', 'Admin\HomeController@index');

        Route::get('/permission/', 'Admin\PermissionController@index');
    });



    //

});

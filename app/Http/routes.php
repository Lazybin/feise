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


Route::group(['middleware' => ['api']], function () {
    Route::resource('api/v1/boot_page','Api\V1\BootPageController',['only' => ['index']]);
});



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

        Route::get('/upload/upload_image', 'Admin\UploadController@upload_image');

        Route::get('/permission/', 'Admin\PermissionController@show');
        Route::get('/permission/index', 'Admin\PermissionController@index');
        Route::post('/permission/store', 'Admin\PermissionController@store');
        Route::post('/permission/update/{id}', 'Admin\PermissionController@update');
        Route::get('/permission/detail/{id}', 'Admin\PermissionController@detail');
        Route::get('/permission/delete/{id}', 'Admin\PermissionController@delete');

        Route::get('/boot_page/', 'Admin\BootPageController@show');
        Route::post('/boot_page/store', 'Admin\BootPageController@store');

    });



    //

});

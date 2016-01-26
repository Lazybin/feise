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
    Route::resource('api/v1/setting/boot_page','Api\V1\BootPageController',['only' => ['index']]);
    Route::resource('api/v1/setting/gift_token_setting','Api\V1\GiftTokenSettingController',['only' => ['index']]);
    Route::resource('api/v1/setting/banner','Api\V1\BannerController',['only' => ['index']]);
    Route::resource('api/v1/themes','Api\V1\ThemesController',['only' => ['index','show']]);
    Route::resource('api/v1/goods','Api\V1\GoodsController',['only' => ['index','show']]);
    Route::resource('api/v1/shopping_cart','Api\V1\ShoppingCartController');
    Route::resource('api/v1/home','Api\V1\HomeController',['only' => ['index','show']]);



    Route::get('api/v1/orders/get_pay_info', 'Api\V1\OrdersController@getPayInfo');
    Route::post('api/v1/orders/notify', 'Api\V1\OrdersController@notify');

    Route::resource('api/v1/orders','Api\V1\OrdersController');

});


Route::post('upload_file','UploadFileController@upload');
Route::post('upload_file/delete','UploadFileController@delete');
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
        Route::get('/home/', 'Admin\HomeController@index');

        Route::get('/upload/upload_image', 'Admin\UploadController@upload_image');

        Route::get('/permission/', 'Admin\PermissionController@show');
        Route::get('/permission/index', 'Admin\PermissionController@index');
        Route::post('/permission/store', 'Admin\PermissionController@store');
        Route::post('/permission/update/{id}', 'Admin\PermissionController@update');
        Route::get('/permission/detail/{id}', 'Admin\PermissionController@detail');
        Route::delete('/permission/delete/{id}', 'Admin\PermissionController@delete');

        Route::get('/boot_page/', 'Admin\BootPageController@show');
        Route::post('/boot_page/store', 'Admin\BootPageController@store');

        Route::get('/gift_token_setting/', 'Admin\GiftTokenSettingController@show');
        Route::get('/gift_token_setting/update/{id}', 'Admin\GiftTokenSettingController@update');

        Route::get('/banner/', 'Admin\BannerController@show');
        Route::get('/banner/index', 'Admin\BannerController@index');
        Route::post('/banner/store', 'Admin\BannerController@store');
        Route::get('/banner/detail/{id}', 'Admin\BannerController@detail');
        Route::post('/banner/update/{id}', 'Admin\BannerController@update');
        Route::delete('/banner/delete/{id}', 'Admin\BannerController@delete');

        Route::get('/category/', 'Admin\CategoryController@show');
        Route::get('/category/index', 'Admin\CategoryController@index');
        Route::post('/category/store', 'Admin\CategoryController@store');
        Route::get('/category/detail/{id}', 'Admin\CategoryController@detail');
        Route::post('/category/update/{id}', 'Admin\CategoryController@update');
        Route::delete('/category/delete/{id}', 'Admin\CategoryController@delete');

        Route::get('/category/get_property/{id}', 'Admin\CategoryController@get_property');
        Route::delete('/category/delete_property/{id}', 'Admin\CategoryController@delete_property');
        Route::post('/category/store_property', 'Admin\CategoryController@store_property');
        Route::get('/category/property_detail/{id}', 'Admin\CategoryController@property_detail');
        Route::post('/category/property_update/{id}', 'Admin\CategoryController@property_update');

        Route::get('/goods/', 'Admin\GoodsController@show');
        Route::get('/goods/index', 'Admin\GoodsController@index');
        Route::post('/goods/store', 'Admin\GoodsController@store');
        Route::delete('/goods/delete/{id}', 'Admin\GoodsController@delete');
        Route::get('/goods/detail/{id}', 'Admin\GoodsController@detail');
        Route::post('/goods/update/{id}', 'Admin\GoodsController@update');


        Route::get('/themes/', 'Admin\ThemesController@show');
        Route::get('/themes/index', 'Admin\ThemesController@index');
        Route::post('/themes/store', 'Admin\ThemesController@store');
        Route::delete('/themes/delete/{id}', 'Admin\ThemesController@delete');
        Route::get('/themes/detail/{id}', 'Admin\ThemesController@detail');
        Route::post('/themes/update/{id}', 'Admin\ThemesController@update');

        Route::get('/subjects/', 'Admin\SubjectController@show');
        Route::get('/subjects/index', 'Admin\SubjectController@index');
        Route::post('/subjects/store', 'Admin\SubjectController@store');
        Route::delete('/subjects/delete/{id}', 'Admin\SubjectController@delete');
        Route::get('/subjects/detail/{id}', 'Admin\SubjectController@detail');
        Route::post('/subjects/update/{id}', 'Admin\SubjectController@update');

        Route::get('/home_manage/', 'Admin\HomeManageController@show');
        Route::get('/home_manage/index', 'Admin\HomeManageController@index');
        Route::post('/home_manage/store', 'Admin\HomeManageController@store');
        Route::delete('/home_manage/delete/{id}', 'Admin\HomeManageController@delete');
        Route::get('/home_manage/detail/{id}', 'Admin\HomeManageController@detail');
        Route::post('/home_manage/update/{id}', 'Admin\HomeManageController@update');

    });



    //

});

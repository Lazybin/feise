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
    Route::resource('api/v1/themes','Api\V1\ThemesController',['only' => ['index','show','update']]);
    Route::resource('api/v1/goods','Api\V1\GoodsController',['only' => ['index','show','update']]);

    Route::post('api/v1/shopping_cart/updates','Api\V1\ShoppingCartController@updates');
    Route::post('api/v1/shopping_cart/delete','Api\V1\ShoppingCartController@delete');
    Route::resource('api/v1/shopping_cart','Api\V1\ShoppingCartController');
    Route::resource('api/v1/home','Api\V1\HomeController',['only' => ['index','show']]);



    Route::get('api/v1/orders/get_pay_info', 'Api\V1\OrdersController@getPayInfo');
    Route::post('api/v1/orders/notify', 'Api\V1\OrdersController@notify');

    Route::resource('api/v1/orders','Api\V1\OrdersController');

    Route::resource('api/v1/activity_classifications','Api\V1\ActivityClassificationsController');

    Route::resource('api/v1/activity_page','Api\V1\ActivityPageController');
    Route::resource('api/v1/free_post','Api\V1\FreePostController');

    Route::post('api/v1/refunds/only_refund_money','Api\V1\RefundsController@onlyRefundMoney');
    Route::post('api/v1/refunds/both_refund','Api\V1\RefundsController@bothRefund');
    Route::resource('api/v1/refunds','Api\V1\RefundsController');

    Route::get('api/v1/wap/banner_detail/{id}','WapController@bannerDetail');
    Route::get('api/v1/wap/goods_detail/{id}','WapController@goodsDetail');
    Route::get('api/v1/wap/themes_description/{id}','WapController@themesDescription');

    Route::get('api/v1/wap/home_navigation_detail/{id}','WapController@homeNavigationDetail');
    Route::get('api/v1/wap/new_year_activity/{user_id}','WapController@newYearActivity');


    Route::resource('api/v1/new_year_active','Api\V1\NewYearActivityController');

    Route::resource('api/v1/home_navigation', 'Api\V1\HomeNavigationController');

    Route::resource('api/v1/area','Api\V1\AreaController');

    Route::resource('api/v1/shipping_address','Api\V1\ShippingAddressController');

    Route::post('api/v1/collection/batch_store','Api\V1\CollectionController@batchStore');
    Route::resource('api/v1/collection','Api\V1\CollectionController');

    Route::resource('api/v1/subject','Api\V1\SubjectController');

    Route::get('api/v1/search/get_search_records', 'Api\V1\SearchController@getSearchRecords');
    Route::resource('api/v1/search','Api\V1\SearchController');

    Route::resource('api/v1/comments','Api\V1\UserCommentsController');

    Route::resource('api/v1/check_in','Api\V1\CheckInController');

    Route::resource('api/v1/present_coupon','Api\V1\PresentCouponController');
    Route::resource('api/v1/user_levels','Api\V1\UserLevelsController');
});


Route::post('upload_file','UploadFileController@upload');
Route::post('upload_file/delete','UploadFileController@delete');

Route::post('notify/weixin','NotifyController@weixin');
Route::post('notify/yinlian','NotifyController@yinlian');

Route::get('test','TestController@index');
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
        Route::get('/gift_token_setting/detail/{id}', 'Admin\GiftTokenSettingController@detail');
        Route::post('/gift_token_setting/update/{id}', 'Admin\GiftTokenSettingController@update');

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

        Route::get('/activity_classification/', 'Admin\ActivityClassificationsController@show');
        Route::get('/activity_classification/index', 'Admin\ActivityClassificationsController@index');
        Route::post('/activity_classification/store', 'Admin\ActivityClassificationsController@store');
        Route::get('/activity_classification/detail/{id}', 'Admin\ActivityClassificationsController@detail');
        Route::delete('/activity_classification/delete/{id}', 'Admin\ActivityClassificationsController@delete');
        Route::post('/activity_classification/update/{id}', 'Admin\ActivityClassificationsController@update');

        Route::get('/activity_goods/', 'Admin\ActivityGoodsController@show');
        Route::get('/activity_goods/index', 'Admin\ActivityGoodsController@index');
        Route::delete('/activity_goods/delete/{id}', 'Admin\ActivityGoodsController@delete');

        Route::get('/free_post/', 'Admin\FreePostController@show');
        Route::get('/free_post/index', 'Admin\FreePostController@index');
        Route::get('/free_post/detail/{id}', 'Admin\FreePostController@detail');
        Route::post('/free_post/update/{id}', 'Admin\FreePostController@update');

        Route::get('/conversion_goods/', 'Admin\ConversionGoodsController@show');
        Route::get('/conversion_goods/index', 'Admin\ConversionGoodsController@index');
        Route::post('/conversion_goods/store', 'Admin\ConversionGoodsController@store');
        Route::get('/conversion_goods/detail/{id}', 'Admin\ConversionGoodsController@detail');
        Route::post('/conversion_goods/update/{id}', 'Admin\ConversionGoodsController@update');
        Route::delete('/conversion_goods/delete/{id}', 'Admin\ConversionGoodsController@delete');



        Route::get('/orders/', 'Admin\OrdersController@show');
        Route::get('/orders/index', 'Admin\OrdersController@index');
        Route::post('/orders/update/{id}', 'Admin\OrdersController@update');

        Route::get('/home_navigation/', 'Admin\HomeNavigationController@show');
        Route::get('/home_navigation/index', 'Admin\HomeNavigationController@index');
        Route::get('/home_navigation/detail/{id}', 'Admin\HomeNavigationController@detail');
        Route::post('/home_navigation/update/{id}', 'Admin\HomeNavigationController@update');


        Route::get('/user_level/', 'Admin\UserLevelsController@show');
        Route::get('/user_level/index', 'Admin\UserLevelsController@index');
        Route::post('/user_level/store', 'Admin\UserLevelsController@store');
        Route::get('/user_level/detail/{id}', 'Admin\UserLevelsController@detail');
        Route::post('/user_level/update/{id}', 'Admin\UserLevelsController@update');
        Route::delete('/user_level/delete/{id}', 'Admin\UserLevelsController@delete');

    });



    //

});

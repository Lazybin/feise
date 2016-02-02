<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Banner;
use App\Model\BaseResponse;
use App\Model\HomeNavigation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Model(
 * id="HomeNavigationAndBanner",
 *  @SWG\Property(name="button_list",type="array",@SWG\Items("HomeNavigation"),description="分类详情"),
 *  @SWG\Property(name="banner_list",type="array",@SWG\Items("Banner"),description="banner列表"),
 * )
 */

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/home_navigation",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class HomeNavigationController extends Controller
{


    /**
     *
     * @SWG\Api(
     *   path="/home_navigation",
     *   description="首页按钮和banner",
     *   @SWG\Operation(
     *     method="GET", summary="获得首页和banner按钮列表", notes="对于按钮列表点击详情时：当type=0的时候调用 APP WAP 页 中的导航按钮详情图片接口来访问详情，当type=1时，直接跳转action字段中的链接",
     *     type="HomeNavigationAndBanner",
     *     @SWG\ResponseMessage(code=0, message="成功")
     *   )
     * )
     */
    public function index(Request $request)
    {
        $response=new BaseResponse();
        $ret=(object)null;
        $button_list=HomeNavigation::orderBy('sort')->orderBy('id')->get();
        $banners=Banner::where('banner_position',0)->get();
        $ret->button_list=$button_list;
        $ret->banner_list=$banners;
        $response->Data=$ret;
        return $response->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

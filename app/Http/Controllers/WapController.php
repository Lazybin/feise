<?php

namespace App\Http\Controllers;

use App\Model\Banner;
use App\Model\Goods;
use App\Model\Themes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/wap",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class WapController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/wap/banner_detail/{id}",
     *   description="APP WAP 页",
     *   @SWG\Operation(
     *     method="GET", summary="banner详情wap", notes="banner详情wap",
     *     type="Goods",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="banner id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function bannerDetail($id){
        $data['content']=Banner::find($id)->toArray();
        return view('wap.banner_detail',$data);
    }

    /**
     *
     * @SWG\Api(
     *   path="/wap/goods_detail/{id}",
     *   @SWG\Operation(
     *     method="GET", summary="商品详情wap", notes="商品详情wap",
     *     type="Goods",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="商品 id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function goodsDetail($id){
        $data['goods']=Goods::find($id)->toArray();
        //echo $data['goods']['detailed_introduction'];exit;
        return view('wap.goods_detail',$data);
    }

    /**
     *
     * @SWG\Api(
     *   path="/wap/themes_description/{id}",
     *   @SWG\Operation(
     *     method="GET", summary="主题描述wap", notes="主题描述wap",
     *     type="Goods",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="商品 id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function themesDescription($id){
        $data['themes']=Themes::find($id)->toArray();
        return view('wap.themes_description',$data);
    }
}

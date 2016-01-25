<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Banner;
use App\Model\BaseResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/setting",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class BannerController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/setting/banner",
     *   description="系统设置",
     *   @SWG\Operation(
     *     method="GET", summary="取得banner", notes="返回的为相对路径，使用时请在前面加上http://120.27.199.121/feise/public",
     *     @SWG\ResponseMessage(code=0, message=""),
     *     @SWG\Parameter(
     *         name="banner_position",
     *         description="banner位置 0-->首页，1-->约惠",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=1
     *     )
     *   )
     * )
     */
    public function index(Request $request)
    {
        $response=new BaseResponse();
        $banner_position=$request->input('banner_position',0);
        $banners=Banner::where('banner_position',$banner_position);
        $response->rows=$banners->get();
        $response->total=$banners->count();
        return $response->toJson();
    }
}

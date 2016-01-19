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
     *     method="GET", summary="取得首页banner", notes="返回的为相对路径，使用时请在前面加上http://120.27.199.121/feise/public",
     *     @SWG\ResponseMessage(code=404, message="page not found")
     *
     *   )
     * )
     */
    public function index()
    {
        $response=new BaseResponse();

        $banners=Banner::all();
        $response->rows=$banners;
        $response->total=Banner::count();
        return $response->toJson();
    }
}

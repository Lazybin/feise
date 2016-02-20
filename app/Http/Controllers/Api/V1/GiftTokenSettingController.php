<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\GiftTokenSetting;
use Illuminate\Http\Request;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/setting",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class GiftTokenSettingController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/setting/gift_token_setting",
     *   @SWG\Operation(
     *     method="GET", summary="返回礼券获取开启状态", notes="返回礼券获取开启状态",type="GiftTokenSetting",
     *     @SWG\ResponseMessage(code=404, message="page not found"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="1:注册领取 2:完善资料领取",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     )
     *
     *   )
     * )
     */
    public function index(Request $request)
    {
        $id=$request->input('id');
        $response=new BaseResponse();
        $status=GiftTokenSetting::find($id);
        $response->Data=$status;
        return $response->toJson();
    }
}

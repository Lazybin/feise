<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Area;
use App\Model\BaseResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/area",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class AreaController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/area",
     *   description="地区分类",
     *   @SWG\Operation(
     *     method="GET", summary="地区分类列表", notes="地区分类列表",
     *     type="Area",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="pid",
     *         description="父id,默认为0---》获取省份",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=0
     *     )
     *   )
     * )
     */
    public function index(Request $request)
    {
        $pid=$request->input('pid',0);
        $response=new BaseResponse();
        $area=Area::where('pid',$pid);
        $rows=$area->orderBy('id')->get()->toArray();
        $response->rows=$rows;
        $response->total=$area->count();
        return $response->toJson();
    }
}

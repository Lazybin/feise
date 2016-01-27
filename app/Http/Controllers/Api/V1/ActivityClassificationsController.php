<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\ActivityClassification;
use App\Model\ActivityClassificationGoods;
use App\Model\BaseResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/activity_classifications",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class ActivityClassificationsController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/activity_classifications",
     *   description="活动",
     *   @SWG\Operation(
     *     method="GET", summary="获得活动分类列表", notes="获得活动分类列表",
     *     type="ActivityClassification",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="PageNum",
     *         description="分页开始位置",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=1
     *     ),@SWG\Parameter(
     *         name="PerPage",
     *         description="取得长度",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=10
     *     )
     *
     *   )
     * )
     */
    public function index(Request $request)
    {
        //
        $start=$request->input('PageNum', 0);
        $length=$request->input('PerPage', 5);
        $start=($start-1)*$length;
        $response=new BaseResponse();
        $home=ActivityClassification::skip($start)->take($length)->orderBy('sort')->orderBy('id','desc');
        $response->rows=$home->get();
        $response->total=ActivityClassification::count();
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
     *
     * @SWG\Api(
     *   path="/activity_classifications/{id}",
     *   @SWG\Operation(
     *     method="GET", summary="获得分类详情", notes="获得分类详情",
     *     type="array",
     *     @SWG\Items("ActivityClassificationGoods"),
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="分类id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     ),@SWG\Parameter(
     *         name="PageNum",
     *         description="分页开始位置",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=1
     *     ),@SWG\Parameter(
     *         name="PerPage",
     *         description="取得长度",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=10
     *     )
     *
     *   )
     * )
     */
    public function show(Request $request,$id)
    {
        $start=$request->input('PageNum', 0);
        $length=$request->input('PerPage', 5);
        $start=($start-1)*$length;
        $response=new BaseResponse();
        $goods_list=ActivityClassificationGoods::where('activity_classification_id',$id);
        $total=$goods_list->count();

        $goods_list=$goods_list->skip($start)->take($length)->orderBy('id','desc');
        $response->rows=$goods_list->get();
        $response->total=$total;
        return $response->toJson();
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

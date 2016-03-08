<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\ActivityClassification;
use App\Model\Banner;
use App\Model\BaseResponse;
use App\Model\Collection;
use App\Model\ConversionGoods;
use App\Model\FreePost;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Model(
 * id="ActivityPage",
 *  @SWG\Property(name="banners",type="Banner",description="banner列表"),
 *  @SWG\Property(name="activityClassification",type="ActivityClassification",description="类型列表"),
 *  @SWG\Property(name="freePost",type="FreePost",description="包邮列表"),
 *  @SWG\Property(name="conversionGoods",type="ConversionGoods",description="兑换列表")
 * )
 */
/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/activity_page",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class ActivityPageController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/activity_page",
     *   description="约惠主页(有更新20160308)",
     *   @SWG\Operation(
     *     method="GET", summary="获得约惠主页内容", notes="获得约惠主页内容",
     *     type="ActivityPage",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=-1
     *     )
     *   )
     * )
     */
    public function index(Request $request)
    {
        $user_id=$request->input('user_id',-1);
        $response=new BaseResponse();
        $ret=(object)null;
        $banners=Banner::where('banner_position',1)->get();
        $ret->banners=$banners;

        //分类
        $activityClassification=ActivityClassification::all();
        $ret->activityClassification=$activityClassification;
        //包邮
        $freePost=FreePost::all();
        $ret->freePost=$freePost;
        //爆品
        $conversionGoods=ConversionGoods::all();

        //$conversionGoods=$conversionGoods->toArray();

        foreach($conversionGoods as &$v){

            $v->has_collection=0;
            if($user_id!=-1){
                $collection=Collection::where('user_id',$user_id)->where('type',0)->where('item_id',$v->goods_id)->first();
                if($collection!=null){
                    $v->has_collection=1;
                }
            }
        }
        $ret->conversionGoods=$conversionGoods;

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

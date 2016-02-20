<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Order;
use App\Model\UserLevel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/user_levels",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class UserLevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     *   path="/user_levels/{user_id}",
     *   description="用户等级(新20160220)",
     *   @SWG\Operation(
     *     method="GET", summary="查询用户等级", notes="查询用户等级",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function show($id)
    {
        $response=new BaseResponse();
        $sum=Order::select(DB::raw('SUM(total_fee) as total_pay'))->where('user_id',$id)->where('status',4)->first()->toArray();
        if($sum==null||$sum['total_pay']==null){
            $sum=0;
        }else{
            $sum=$sum['total_pay'];
        }

        $level=UserLevel::where('sum_lowest','<=',$sum)->where('sum_highest','>',$sum)->first()->toArray();
        $ret['level']=$level['name'];
        $response->Data=$ret;
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

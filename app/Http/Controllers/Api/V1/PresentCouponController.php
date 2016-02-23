<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\GiftTokenSetting;
use App\Model\PresentCouponRecords;
use App\Model\ShippingAddress;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/present_coupon",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class PresentCouponController extends Controller
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
     *
     * @SWG\Api(
     *   path="/present_coupon",
     *   description="赠送礼券（新20160220）",
     *   @SWG\Operation(
     *     method="POST", summary="赠送礼券", notes="赠送礼券,sum值-1 是未填写收货地址 -2是已经赠送过 -3是关闭赠送  非负是 赠送成功
    ",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     ),@SWG\Parameter(
     *         name="access_token",
     *         description="accessToken",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="string"
     *     ),@SWG\Parameter(
     *         name="account",
     *         description="用户账号",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="string"
     *     ),@SWG\Parameter(
     *         name="type",
     *         description="类型，1--》注册赠送礼券，2---》完善资料赠送",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="string"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $response=new BaseResponse();
        $user_id=$request->input('user_id',-1);
        $account=$request->input('account');
        $type=$request->input('type');
        $accessToken=$request->input('access_token');

        if($user_id!=-1||$user_id!=0){
            //检测是否赠送过
            $hasRecords=PresentCouponRecords::where('user_id',$user_id)->where('type',$type)->first();
            $shippingAddress=ShippingAddress::where('user_id',$user_id)->first();
            if($type==2&&$shippingAddress==null){//未填写收货地址
                $ret['sum']=-1;
                $response->Data=$ret;
                return $response->toJson();
            }
            if($hasRecords!=null){
                $ret['sum']=-2;
                $response->Data=$ret;
                return $response->toJson();
            }
            $giftTokenSetting=GiftTokenSetting::find($type);
            if($giftTokenSetting!=null&&$giftTokenSetting->status==1){
                //赠送礼券接口
                $apiParam=[
                    'accessToken'=>$accessToken,
                    'coupon'=>$giftTokenSetting->sum,
                    'expiry'=>date('Y-m-d H:i:s' ,strtotime('+3 month'))
                ];
                $this->post($apiParam);

                //存储记录
                $couponRecrods=new PresentCouponRecords();
                $couponRecrods->user_id=$user_id;
                $couponRecrods->account=$account;
                $couponRecrods->sum=$giftTokenSetting->sum;
                $couponRecrods->type=$type;
                $couponRecrods->save();
                $ret['sum']=$giftTokenSetting->sum;

                $response->Data=$ret;
            }else{
                $ret['sum']=-3;
                $response->Data=$ret;
                return $response->toJson();
            }
        }

        return $response->toJson();
    }

    private function post($data=null)
    {
        $request_url='http://112.124.27.45:8080/zhmf/member/consumerCoupon/presentCoupon';
        $ch = curl_init ();
        $header = array ();
        $header [] = 'Content-Type: application/json';
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_URL, $request_url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
        curl_setopt ( $ch, CURLOPT_POST, 1 ); //启用POST提交
        if($data!=null)
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data));
        $file_contents = curl_exec ( $ch );
        curl_close ( $ch );
        return $file_contents;
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

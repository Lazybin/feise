<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\CheckInRecords;
use App\Model\GiftTokenSetting;
use App\Model\PresentCouponRecords;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/check_in",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class CheckInController extends Controller
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
     *   path="/check_in",
     *   description="普通签到（新20160218）",
     *   @SWG\Operation(
     *     method="POST", summary="签到", notes="签到，如果已经签过到或者没有登录，data=0，如果签到成功data=1",
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
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $response=new BaseResponse();
        $user_id=$request->input('user_id',-1);
        $account=$request->input('account');
        $accessToken=$request->input('access_token');
        $ret['result']=0;
        $response->Data=$ret;
        if($user_id!=-1||$user_id!=0){
            $t = time();
            $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
            $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
            $today=CheckInRecords::where('user_id',$user_id)->where('created_at','>=',date('Y-m-d H:i:s',$start))
                ->where('created_at','<=',date('Y-m-d H:i:s',$end))->first();

            if($today==null){
                $records=new CheckInRecords();
                $records->user_id=$user_id;
                $records->save();
                $ret['result']=1;
                //签到成功，赠送礼券
                $giftTokenSetting=GiftTokenSetting::find(3);
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
                    $couponRecrods->type=3;
                    $couponRecrods->save();
                }


                $response->Data=$ret;
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

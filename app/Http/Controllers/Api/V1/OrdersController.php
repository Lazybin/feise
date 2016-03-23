<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\ActivityClassificationGoods;
use App\Model\ConversionGoods;
use App\Model\FreePostGoods;
use App\Model\HomeButtonGoods;
use App\Model\HomeButtonGoodsBuyRecords;
use App\Model\UseCouponRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Model\BaseResponse;
use App\Model\Goods;
use App\Model\Order;
use App\Model\OrderGoods;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/orders",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class OrdersController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/orders",
     *   description="订单（新20160309）",
     *   @SWG\Operation(
     *     method="GET", summary="获得用户订单列表", notes="获得用户订单列表",
     *     type="Order",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     ),@SWG\Parameter(
     *         name="status",
     *         description="状态，-1：全部，1：待发货,3：已发货,4：历史订单",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=-1
     *     ),
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
        $start=$request->input('PageNum', 0);
        $length=$request->input('PerPage', 5);
        $status=$request->input('status',-1);
        $user_id=$request->input('user_id');
        $start=($start-1)*$length;
        $response=new BaseResponse();
        $order=Order::where('user_id',$user_id)->whereNull('delete_at');
        switch($status){
            case 4:
                $order=$order->where('status',$status);
                break;
            case 3:
                $order=$order->where('status',$status);
                break;
            case 1:
                $order=$order->where('status',$status);
                break;
            default:
                break;

        }
        $rows=$order->skip($start)->take($length)->orderBy('id','desc')->get()->toArray();
        foreach($rows as &$row){
            foreach($row['goods_list'] as &$goods){
                $goods['properties']=json_decode($goods['properties']);
            }
        }
        $response->rows=$rows;
        $response->total=$order->count();
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
     *
     * @SWG\Api(
     *   path="/orders",
     *   @SWG\Operation(
     *     method="POST", summary="生成订单", notes="生成订单",type="Order",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="order_info",
     *         description="提交的订单信息",
     *         paramType="body",
     *         required=true,
     *         type="newOrderParams"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        $accessToken=$content->accessToken;
        $goodsList=$content->goodsList;
        $total_fee=0;
        $coupon_total=0;
        foreach ($goodsList as $g){
            $goods=Goods::find($g->goods_id);
            if($goods!=null){
                //检查是否购买过新用户福利（0元福利）

                //是否是新用户福利商品
                $homeButtonGoods=HomeButtonGoods::where('goods_id',$goods->id)->first();
                if($homeButtonGoods!=null){
                    //判断是否购买过
                    $homeButtonGoodsBuyRecords=HomeButtonGoodsBuyRecords::where('home_button_goods_id',$goods->id)->first();
                    if($homeButtonGoodsBuyRecords!=null){
                        $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
                        $response->Message="商品".$goods->name."为新用户福利，只能购买一次，您已经购买过了";
                        DB::rollback();
                        return $response->toJson();
                    }else{
                        $newHomeButtonGoodsRecords=new HomeButtonGoodsBuyRecords();
                        $newHomeButtonGoodsRecords->user_id=$content->user_id;
                        $newHomeButtonGoodsRecords->home_button_goods_id=$homeButtonGoods->id;
                        $newHomeButtonGoodsRecords->save();
                    }
                }

                //如果在约惠列表里，不减去优惠金额 要扣券
                if(ActivityClassificationGoods::where('goods_id',$goods->id)->count()>0||
                    FreePostGoods::where('goods_id',$goods->id)->count()>0||
                    ConversionGoods::where('goods_id',$goods->id)->count()>0
                ){
                    $total_fee=$total_fee+($goods->price)*$g->num;
                    $coupon_total=$coupon_total+(($goods->coupon_amount)*$g->num);
                    $goods->num=$goods->num-$g->num;
                    if($goods->num<0){
                        $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
                        $response->Message="商品库存不够";
                        DB::rollback();
                        return $response->toJson();
                    }
                    $goods->save();
                    continue;
                }

                if($g->use_coupon==1){
                    $total_fee=$total_fee+(($goods->price)*$g->num)-(($goods->coupon_amount)*$g->num);
                    $coupon_total=$coupon_total+(($goods->coupon_amount)*$g->num);

                }else{
                    $total_fee=$total_fee+($goods->price)*$g->num;
                }
                $goods->num=$goods->num-$g->num;
                if($goods->num<0){
                    $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
                    $response->Message="商品库存不够";
                    DB::rollback();
                    return $response->toJson();
                }
                $goods->save();

            }else{
                $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
                $response->Message="get goods failed!";
                DB::rollback();
                return $response->toJson();
            }

        }
        unset($content->goodsList);

//        DB::rollback();s
//        echo $coupon_total;exit;

        //检测礼券充足

        $apiParam=[
            'accessToken'=>$accessToken,
            'coupon'=>$coupon_total
        ];
        $res=$this->post('/zhmf/member/consumerCoupon/isCouponEnough',$apiParam);
        $res=json_decode($res);
        if($res->Code==0&&$res->Data->enough==false){
            $response->Code=-1;
            $response->Message="礼券不足";
            DB::rollback();
            return $response->toJson();
        }

        if($total_fee<=0)
            $total_fee=0;

        $content->total_fee=$total_fee;
        $content->shipping_fee=10;//总运费
        $content->out_trade_no=$this->buildOrderNo();

        if($total_fee==0){
            $content->status=1;


        }
        $order=Order::create((array)$content);

        $useCouponRecords=new UseCouponRecords();
        $useCouponRecords->user_id=$content->user_id;
        $useCouponRecords->order_id=$order->id;
        $useCouponRecords->access_token=$accessToken;
        $useCouponRecords->coupon=$coupon_total;

        if($total_fee==0){
            $useCouponRecords->status=2;
            $apiParam=[
                'accessToken'=>$useCouponRecords->access_token,
                'coupon'=>$useCouponRecords->coupon,
                'orderId'=> $content->out_trade_no
            ];
            $res=$this->post('/zhmf/member/consumerCoupon/useCoupon',$apiParam);
            $res=json_decode($res);

            if($res->Code==0){
                $useCouponRecords->status=1;
            }
        }else{
            $useCouponRecords->status=0;
        }

        $useCouponRecords->save();

        foreach($goodsList as $v){
            $orderGoods=new OrderGoods();
            $orderGoods->order_id=$order->id;
            $orderGoods->goods_id=$v->goods_id;
            $orderGoods->num=$v->num;
            $orderGoods->properties=json_encode($v->properties);
            if(isset($v->message)){
                $orderGoods->message=$v->message;
            }

            $orderGoods->save();
        }

        $response->Data=$order;
        DB::commit();
        return $response->toJson();

        //var_dump($content);
        //echo $this->sign_request(['array'],'../config/rsa_private_key.pem');
    }

    private function post($url,$data=null)
    {
        $request_url='http://zhihuimeiye.net'.$url;
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
     * 得到新订单号
     * @return  string
     */
    private function buildOrderNo()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);

        return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public function rsa_sign($data, $rsaPrivateKeyFilePath) {
        $priKey = file_get_contents ( $rsaPrivateKeyFilePath );

        $res = openssl_get_privatekey ( $priKey );
        openssl_sign ( $data, $sign, $res );
        openssl_free_key ( $res );
        $sign = base64_encode ( $sign );
        return $sign;
    }
    public function sign_request($params, $rsaPrivateKeyFilePath) {
        return $this->rsa_sign ( $params, $rsaPrivateKeyFilePath );
    }

    public function rsa_verify($data, $sign, $rsaPublicKeyFilePath) {
        // 读取公钥文件
        $pubKey = file_get_contents ( $rsaPublicKeyFilePath );

        // 转换为openssl格式密钥
        $res = openssl_get_publickey ( $pubKey );

        // 调用openssl内置方法验签，返回bool值
        $result = ( bool ) openssl_verify ( $data, base64_decode ( $sign ), $res );

        // 释放资源
        openssl_free_key ( $res );

        return $result;
    }
    public function rsaCheckV2($params, $rsaPublicKeyFilePath) {
        $sign = $params ['sign'];
        $params ['sign'] = null;

        return $this->rsa_verify ( $this->getSignContent ( $params ), $sign, $rsaPublicKeyFilePath );
    }

    public function getSignContent($params) {
        ksort ( $params );

        $stringToBeSigned = "";
        $i = 0;
        foreach ( $params as $k => $v ) {
            if (false === $this->checkEmpty ( $v ) && "@" != substr ( $v, 0, 1 )) {
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i ++;
            }
        }
        unset ( $k, $v );
        return $stringToBeSigned;
    }
    /**
     * 校验$value是否非空
     * if not set ,return true;
     * if is null , return true;
     */
    public function checkEmpty($value) {
        if (! isset ( $value ))
            return true;
        if ($value === null)
            return true;
        if (trim ( $value ) === "")
            return true;

        return false;
    }


    private function getPayInfoStr($out_trade_no)
    {
        $order=Order::where('out_trade_no',$out_trade_no)->first();

        // 签约合作者身份ID
        $payInfo = "partner="."\"".Order::PARTNER."\"";

		// 签约卖家支付宝账号
		$payInfo .= "&seller_id="."\"".Order::SELLER."\"";

//		// 商户网站唯一订单号
        $payInfo .= "&out_trade_no="."\"".$order->out_trade_no."\"";
//
//		// 商品名称
        $subject='';
        $body='';
        $goodsList=$order->goods_list;
        foreach($goodsList as $g){
            $subject.=$g['goods']['name'].'+';
            $body.=$g['goods']['goods_description'].'+';
        }
        if(mb_strlen($subject)>120){
            $subject=mb_substr($subject,0,120,'utf-8');
            $subject.='...';
        }else{
            $subject=mb_substr($subject,0,mb_strlen($subject)-1,'utf-8');
        }
        if(mb_strlen($body)>500){
            $body=mb_substr($body,0,500,'utf-8');
            $body.='...';
        }else{
            $body=mb_substr($body,0,mb_strlen($body)-1,'utf-8');
        }
        if($body==''){
            $body=$subject;
        }
        //$order->total_fee=0.01;

        $payInfo .= "&subject="."\"".$subject. "\"";

//		// 商品详情
        $payInfo .= "&body=" . "\"" . $body . "\"";
//
//		// 商品金额
        $payInfo .= "&total_fee=" . "\"" . $order->total_fee . "\"";
//
//		// 服务器异步通知页面路径
        $payInfo .= "&notify_url=" . "\"" . "http://120.27.199.121/feise/public/api/v1/orders/notify" ."\"";
//
//		// 服务接口名称， 固定值
		$payInfo .= "&service=\"mobile.securitypay.pay\"";
//
//		// 支付类型， 固定值
        $payInfo .= "&payment_type=\"1\"";
//
//		// 参数编码， 固定值
        $payInfo .= "&_input_charset=\"utf-8\"";
//
//		// 设置未付款交易的超时时间
//		// 默认30分钟，一旦超时，该笔交易就会自动被关闭。
//		// 取值范围：1m～15d。
//		// m-分钟，h-小时，d-天，1c-当天（无论交易何时创建，都在0点关闭）。
//		// 该参数数值不接受小数点，如1.5h，可转换为90m。
		$payInfo .= "&it_b_pay=\"30m\"";



        $sign=$this->sign_request($payInfo,'../config/rsa_private_key.pem');
        $sign=urlencode($sign);
        //echo $sign;exit;
        $payInfo .= "&sign=\"$sign\"";
        $payInfo .= "&sign_type=\"RSA\"";
        return $payInfo;
    }

    public function notify(Request $request)
    {
        $out_trade_no=$request->input('out_trade_no');
        $trade_status=$request->input('trade_status');
        if($out_trade_no!=null&&$trade_status!=null&&($trade_status=='TRADE_FINISHED'||$trade_status=='TRADE_SUCCESS')){
            $order=Order::where('out_trade_no',$out_trade_no)->first();
            if($order!=null){
                $orderId=$order->id;
                $order->status=1;
                $time=$request->input('gmt_payment');
                if($time==null){
                    $time=date("Y-m-d H:i:s",time());
                }
                $order->payment_time=$time;
                $order->payment_way=1;
                $order->save();

                $useCouponRecords=UseCouponRecords::where('order_id',$orderId)->first();

                if($useCouponRecords!=null){
                    //礼券金额
                    $useCouponRecords->status=2;
                    $apiParam=[
                        'accessToken'=>$useCouponRecords->access_token,
                        'coupon'=>$useCouponRecords->coupon,
                        'orderId'=>$out_trade_no
                    ];
                    $res=$this->post('/zhmf/member/consumerCoupon/useCoupon',$apiParam);
                    $res=json_decode($res);

                    if($res->Code==0){
                        $useCouponRecords->status=1;
                    }
                    $useCouponRecords->save();
                }



            }
        }
        echo 'success';
//        $params=$request->all();
//        $params=(array)$params;
//        Log::info(json_encode($params));
//        if($this->rsaCheckV2($params,'../config/alipay_rsa_public_key.pem')){
//            Log::info('验证成功');
//            echo 'success';
//        }else{
//            Log::info('验证失败');
//        }
    }

    /**
     *
     * @SWG\Api(
     *   path="/orders/{id}",
     *   description="订单详情",
     *   @SWG\Operation(
     *     method="GET", summary="获得订单详情", notes="获得订单详情",
     *     type="Order",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="out_trade_no",
     *         description="订单号",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function show($out_trade_no)
    {
        $response=new BaseResponse();
        $order=Order::where('out_trade_no',$out_trade_no)->first()->toArray();
        foreach($order['goods_list'] as &$goods){
            $goods['properties']=json_decode($goods['properties']);
        }
        $response->Data=$order;
        return $response->toJson();
    }

    /**
     *
     * @SWG\Api(
     *   path="/orders/remind",
     *   description="提醒发货",
     *   @SWG\Operation(
     *     method="GET", summary="提醒发货", notes="提醒发货",
     *     type="Order",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="out_trade_no",
     *         description="订单号",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function remind(Request $request){
        $out_trade_no=$request->input('out_trade_no');
        $response=new BaseResponse();
        $order=Order::where('out_trade_no',$out_trade_no)->first();
        $order->is_remind=1;
        $order->save();
        return $response->toJson();

    }
    /**
     *
     * @SWG\Api(
     *   path="/orders/get_pay_info",
     *   @SWG\Operation(
     *     method="GET", summary="支付字符串", notes="支付字符串",type="string",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="out_trade_no",
     *         description="订单号",
     *         paramType="query",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="type",
     *         description="支付方式0-->支付宝，1--->微信，2--->银联",
     *         paramType="query",
     *         required=true,
     *         type="string"
     *     )
     *   )
     * )
     */
    public function getPayInfo(Request $request)
    {
        $response=new BaseResponse();
        $out_trade_no=$request->input('out_trade_no');
        $type=$request->input('type',0);

        if($out_trade_no==null||$type==null){
            $response->Code=BaseResponse::CODE_ERROR_CHECK;
            $response->Message='缺少参数';
            return $response->toJson();
        }
        if($type==0){
            $data['payInfo']=$this->getPayInfoStr($out_trade_no);
        }else if($type==1){
            $t=$this->generateWeiXinPrepayOrder($out_trade_no);
            if($t==false)
            {
                $response->Code=BaseResponse::CODE_ERROR_CHECK;
                $response->Message='服务器内部错误，错误码：108';
                return $response->toJson();
            }
            $data['weixinPayInfo']=$this->getWeiXinPayParameter($t['responseObj']->prepay_id);
        }else{
            include_once '../vendor/yinlian_sdk/acp_service.php';

            $order=Order::where('out_trade_no',$out_trade_no)->first();
            $params = array(

                //以下信息非特殊情况不需要改动
                'version' => '5.0.0',                 //版本号
                'encoding' => 'utf-8',				  //编码方式
                'txnType' => '01',				      //交易类型
                'txnSubType' => '01',				  //交易子类
                'bizType' => '000201',				  //业务类型
                'frontUrl' =>  SDK_FRONT_NOTIFY_URL,  //前台通知地址
                'backUrl' => SDK_BACK_NOTIFY_URL,	  //后台通知地址
                'signMethod' => '01',	              //签名方法
                'channelType' => '08',	              //渠道类型，07-PC，08-手机
                'accessType' => '0',		          //接入类型
                'currencyCode' => '156',	          //交易币种，境内商户固定156

                //TODO 以下信息需要填写
                'merId' => '777290058125654',		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
                'orderId' =>$out_trade_no,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
                'txnTime' => date('YmdHis',time()),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
                'txnAmt' => ($order->total_fee)*100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据

                //TODO 其他特殊用法请查看 pages/api_05_app/special_use_purchase.php
            );


            \AcpService::sign ( $params ); // 签名
            $url = SDK_App_Request_Url;

            $result_arr = \AcpService::post ($params,$url);
            if(count($result_arr)<=0) { //没收到200应答的情况
                $response->Code=BaseResponse::CODE_ERROR_CHECK;
                $response->Message='服务器内部错误，错误码：108';
                return $response->toJson();
            }

            //printResult ($url, $params, $result_arr ); //页面打印请求应答数据

            if (!\AcpService::validate ($result_arr) ){
                $response->Code=BaseResponse::CODE_ERROR_CHECK;
                $response->Message='应答报文验签失败';
                return $response->toJson();
            }


            if ($result_arr["respCode"] == "00"){
                //成功
                //TODO
                //var_dump(gettype($result_arr["tn"]));exit;
                $data["yinlianPayInfo"]="".(string)$result_arr["tn"]."";
            } else {
                $response->Code=BaseResponse::CODE_ERROR_CHECK;
                $response->Message='服务器内部错误，错误码：108';
                return $response->toJson();
            }

        }
        $response->Data=$data;
        return $response->toJson();
    }
    public function getWeiXinPayParameter($prepay_id)
    {
        $prepayid=(array)$prepay_id;

        $parameters["appid"]='wxad2738e1199a71b8';
        $parameters['partnerid']='1312519501';
        $parameters['prepayid']=$prepayid[0];
        $parameters["timestamp"]=time();
        $parameters["noncestr"]=$this->greatRand();//随机字符串，丌长于 32 位
        $parameters["package"]="Sign=WXPay";
        $parameters['sign']=$this->getWeiXinSign($parameters);
        //$parameters['paySign']=$sign;
        return $parameters;
    }

    /**
     * 生成随机数
     *
     */
    public function greatRand($len=30){
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
        $t1='';
        for($i=0;$i<$len;$i++){
            $j=rand(0,35);
            $t1 .= $str[$j];
        }
        return $t1;
    }

    private function getWeiXinSign($parameters){
        $partnerkey="bc7296f8811efdf31c35da2c5d48d316";

        if (null == $partnerkey || "" == $partnerkey ) {
            return false;
        }

        ksort($parameters);
        $unSignParaString=$this->formatQueryParaMap($parameters, false);
        return $this->sign($unSignParaString,$partnerkey);

    }
    private function sign($content, $key) {
        if (null == $key) {
            return false;
        }
        if (null == $content) {
            return false;
        }
        $signStr = $content . "&key=" . $key;

        return strtoupper(md5($signStr));

    }

    private function formatQueryParaMap($paraMap, $urlencode){
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v){
            if (null != $v && "null" != $v && "sign" != $k) {
                if($urlencode){
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar="";
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    private function generateWeiXinPrepayOrder($out_trade_no)
    {
        $order=Order::where('out_trade_no',$out_trade_no)->first();

        $parameters["appid"]='wxad2738e1199a71b8';
        $parameters["mch_id"]='1312519501';
        $parameters["nonce_str"]=$this->greatRand();

        $subject='';
        $goodsList=$order->goods_list;
        foreach($goodsList as $g){
            $subject.=$g['goods']['name'].'+';
        }
        if(mb_strlen($subject)>120){
            $subject=mb_substr($subject,0,120,'utf-8');
            $subject.='...';
        }else{
            $subject=mb_substr($subject,0,mb_strlen($subject)-1,'utf-8');
        }


        $parameters["body"]=$subject;
        $parameters["attach"]='';
        $parameters["total_fee"]=($order->total_fee)*100;
        $parameters["trade_type"]="APP";
        $parameters["notify_url"]="http://120.27.199.121/feise/public/notify/weixin";

        $parameters["out_trade_no"]=$out_trade_no;

        $parameters["spbill_create_ip"]="127.0.0.1";

        $sign=$this->getWeiXinSign($parameters);
        if($sign!=false)
        {
            $parameters['sign']=$sign;
            $postXml= $this->arrayToXml($parameters);
            if($postXml==false)
            {
                return false;
            }

            $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
            $responseXml = $this->curlPostSsl($url, $postXml);
            $responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);

            if($responseObj->return_code=='SUCCESS'&&$responseObj->result_code=='SUCCESS')
            {
                $ret['responseObj']=$responseObj;
                $ret['out_trade_no']= $parameters["out_trade_no"];
                return $ret;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    }

    private function curlPostSsl($url, $vars, $second=30,$aHeader=array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        //cert 与 key 分别属于两个.pem文件
//        curl_setopt($ch,CURLOPT_SSLCERT,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'apiclient_cert.pem');
//        curl_setopt($ch,CURLOPT_SSLKEY,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'apiclient_key.pem');
//        curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'rootca.pem');


        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
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
     *
     * @SWG\Api(
     *   path="/orders/{out_trade_no}",
     *   @SWG\Operation(
     *     method="PUT", summary="更新订单状态", notes="更新订单状态",type="string",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="out_trade_no",
     *         description="订单号",
     *         paramType="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         description="状态 2：取消订单 4：确认收货",
     *         paramType="query",
     *         required=true,
     *         type="string"
     *     )
     *   )
     * )
     */
    public function update(Request $request, $out_trade_no)
    {
        DB::beginTransaction();
        $response=new BaseResponse();
        $status=$request->input('status');
        if($out_trade_no==null){
            $response->Code=BaseResponse::CODE_ERROR_CHECK;
            $response->Message='缺少参数';
            return $response->toJson();
        }
        $order=Order::where('out_trade_no',$out_trade_no)->first();
        if($order==null){
            $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
            $response->Message='未找到对应项目';
            return $response->toJson();
        }
        $order->status=$status;
        $order->save();
        if($status==2){
            $goodsList=OrderGoods::where('order_id',$order->id)->get()->toArray();
            foreach($goodsList as $g){
                $goods=Goods::find($g['goods_id']);
                if($goods==null){
                    $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
                    $response->Message="商品不存在";
                    DB::rollback();
                    return $response->toJson();
                }
                $goods->num=$goods->num+$g['num'];
                $goods->save();

                //是否是新用户福利商品
                $homeButtonGoods=HomeButtonGoods::where('goods_id',$goods->id)->first();
                if($homeButtonGoods!=null){
                    //判断是否购买过
                    $homeButtonGoodsBuyRecords=HomeButtonGoodsBuyRecords::where('home_button_goods_id',$homeButtonGoods->id)->first();
                    if($homeButtonGoodsBuyRecords!=null){
                        $homeButtonGoodsBuyRecords->delete();
                    }
                }
            }
        }
        DB::commit();
        return $response->toJson();
    }

    /**
     *
     * @SWG\Api(
     *   path="/orders/{out_trade_no}",
     *   @SWG\Operation(
     *     method="DELETE", summary="删除订单", notes="删除订单",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="out_trade_no",
     *         description="订单号",
     *         paramType="path",
     *         required=true,
     *         type="string"
     *     ),
     *   )
     * )
     */
    public function destroy($out_trade_no)
    {
        $response=new BaseResponse();
        $order=Order::where('out_trade_no',$out_trade_no)->first();
        if($order!=null){
            $order->delete_at=date("Y-m-d H:i:s",time());
            $order->save();
        }
        return $response->toJson();
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        $goodsList=$content->goodsList;
        $total_fee=0;
        foreach ($goodsList as $g){
            $goods=Goods::select('price','coupon_amount')->find($g->goods_id);
            if($goods!=null){
                if($g->use_coupon==1)
                    $total_fee=$total_fee+$goods->price-$goods->coupon_amount;
            }else{
                $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
                $response->Message="get goods failed!";
                return $response->toJson();
            }

        }
        unset($content->goodsList);

        $content->total_fee=$total_fee;
        $content->out_trade_no=$this->buildOrderNo();
        $order=Order::create((array)$content);
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
        return $response->toJson();

        //var_dump($content);
        //echo $this->sign_request(['array'],'../config/rsa_private_key.pem');
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
        $params ['sign_type'] = null;
        $data=$this->getSignContent ( $params );
        Log::info($data);

        return $this->rsa_verify ( $data, $sign, $rsaPublicKeyFilePath );
    }

    protected function getSignContent($params) {
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
    protected function checkEmpty($value) {
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
        $order->total_fee=0.01;

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
        $params=$request->all();
        Log::info(json_encode($params));
        if($this->rsaCheckV2($params,'../config/rsa_public_key.pem')){
            Log::info('验证成功');
            echo 'success';
        }else{
            Log::info('验证失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
//        $response=new BaseResponse();
//        $type=$request->input('type','detail');
//        if($type=='detail'){
//
//        }else{
//
//        }
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
     *     )
     *   )
     * )
     */
    public function getPayInfo(Request $request)
    {
        $response=new BaseResponse();
        $out_trade_no=$request->input('out_trade_no');
        if($out_trade_no==null){
            $response->Code=BaseResponse::CODE_ERROR_CHECK;
            $response->Message='缺少参数';
            return $response->toJson();
        }
        $data['payInfo']=$this->getPayInfoStr($out_trade_no);
        $response->Data=$data;
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

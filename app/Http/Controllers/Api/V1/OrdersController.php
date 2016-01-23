<?php

namespace App\Http\Controllers\Api\V1;

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
     *   path="/orders",
     *   @SWG\Operation(
     *     method="POST", summary="生成订单", notes="生成订单",
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
            $orderGoods->message=json_encode($v->message);
            $orderGoods->save();
        }

        var_dump($content);
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
        return $this->rsa_sign ( $this->getSignContent ( $params ), $rsaPrivateKeyFilePath );
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

    private function getPayInfo()
    {
        // 签约合作者身份ID
        //$payInfo = "partner="."\"".PARTNER."\"";

		// 签约卖家支付宝账号
//		orderInfo += "&seller_id=" + "\"" + SELLER + "\"";
//
//		// 商户网站唯一订单号
//		orderInfo += "&out_trade_no=" + "\"" + getOutTradeNo() + "\"";
//
//		// 商品名称
//		orderInfo += "&subject=" + "\"" + subject + "\"";
//
//		// 商品详情
//		orderInfo += "&body=" + "\"" + body + "\"";
//
//		// 商品金额
//		orderInfo += "&total_fee=" + "\"" + price + "\"";
//
//		// 服务器异步通知页面路径
//		orderInfo += "&notify_url=" + "\"" + "http://notify.msp.hk/notify.htm" +
//
//            "\"";
//
//		// 服务接口名称， 固定值
//		orderInfo += "&service=\"mobile.securitypay.pay\"";
//
//		// 支付类型， 固定值
//		orderInfo += "&payment_type=\"1\"";
//
//		// 参数编码， 固定值
//		orderInfo += "&_input_charset=\"utf-8\"";
//
//		// 设置未付款交易的超时时间
//		// 默认30分钟，一旦超时，该笔交易就会自动被关闭。
//		// 取值范围：1m～15d。
//		// m-分钟，h-小时，d-天，1c-当天（无论交易何时创建，都在0点关闭）。
//		// 该参数数值不接受小数点，如1.5h，可转换为90m。
//		orderInfo += "&it_b_pay=\"30m\"";
//
//		// extern_token为经过快登授权获取到的alipay_open_id,带上此参数用户将使用授权
//
//的账户进行支付
//		// orderInfo += "&extern_token=" + "\"" + extern_token + "\"";
//
//		// 支付宝处理完请求后，当前页面跳转到商户指定页面的路径，可空
//		orderInfo += "&return_url=\"m.alipay.com\"";
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

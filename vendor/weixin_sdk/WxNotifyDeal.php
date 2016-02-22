<?php

/**
 * Created by PhpStorm.
 * User: hm01
 * Date: 2016/2/22
 * Time: 15:35
 */
require_once $_SERVER['DOCUMENT_ROOT']."/mywww/feise/vendor/weixin_sdk/WxPay.Api.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/mywww/feise/vendor/weixin_sdk/WxPay.Notify.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/mywww/feise/vendor/weixin_sdk/log.php';
class WxNotifyDeal extends WxPayNotify
{
    private $log;

    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set ( 'Asia/Shanghai' );

        //初始化日志
        $logHandler= new CLogFileHandler("../storage/logs/".date('Y-m-d').'wx_notify.log');
        $this->log = Log::Init($logHandler, 15);
    }
    //查询订单
    public function Queryorder($transaction_id)
    {

        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }


    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("call back:" . json_encode($data));

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        //根据订单号判定用户数据


        $order=\App\Model\Order::where('out_trade_no',$data['out_trade_no'])->first();
        if($order!=null){
            $order->status=1;
            $order->payment_time=$data['time_end'];
            $order->save();
            return true;
        }else{
            return false;
        }
    }
}
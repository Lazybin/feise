<?php

namespace App\Http\Controllers;
require_once "../vendor/weixin_sdk/WxNotifyDeal.php";
use App\Model\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NotifyController extends Controller
{
    //
    public function weixin(){
        $deal=new \WxNotifyDeal();
        $deal->Handle(false);
    }

    public function yinlian(){
        include_once '../vendor/yinlian_sdk/acp_service.php';
        if (isset ( $_POST ['signature'] )) {
            if(\AcpService::validate ( $_POST )&&($_POST['respCode'] == '00'||$_POST['respCode'] == 'A6')) {
                $order = Order::where('out_trade_no', $_POST ['orderId'])->first();
                if ($order != null) {
                    $order->status = 1;
                    $time = date("Y-m-d H:i:s", time());
                    $order->payment_time = $time;
                    $order->save();
                    echo 'success';
                }
            }else{
                @header('HTTP/1.1 500 Internal Server Error');
            }
        } else {
            @header('HTTP/1.1 500 Internal Server Error');
        }
    }

}

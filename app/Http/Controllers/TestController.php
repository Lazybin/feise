<?php

namespace App\Http\Controllers;

use App\Model\Banner;
use App\Model\Goods;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class TestController extends Controller
{

    public function index(){
        $apiParam=[
            'accessToken'=>'546ECC734D76F640F9B24695461A5A91',
            'coupon'=>15,
            'expiry'=>date('Y-m-d H:i:s' ,strtotime('+3 month'))
        ];
        var_dump($apiParam);

        var_dump($this->post($apiParam));
        //return view('test2');{"accessToken":"546ECC734D76F640F9B24695461A5A91","coupon":"15","expiry":"2016-05-19 12:00:00"}
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

    public function bannerDetail(){
        $data['content']=Banner::find(2)->toArray();
        return view('wap.banner_detail',$data);
    }


    public function goodsDetail(){
        $data['goods']=Goods::find(20)->toArray();
        //echo $data['goods']['detailed_introduction'];exit;
        return view('wap.goods_detail',$data);
    }
}

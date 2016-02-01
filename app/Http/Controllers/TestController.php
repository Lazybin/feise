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

        return view('test');
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

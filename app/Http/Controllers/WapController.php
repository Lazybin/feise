<?php

namespace App\Http\Controllers;

use App\Model\Banner;
use App\Model\Goods;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WapController extends Controller
{
    public function bannerDetail($id){
        $data['content']=Banner::find($id)->toArray();
        return view('wap.banner_detail',$data);
    }


    public function goodsDetail($id){
        $data['goods']=Goods::find($id)->toArray();
        //echo $data['goods']['detailed_introduction'];exit;
        return view('wap.goods_detail',$data);
    }
}

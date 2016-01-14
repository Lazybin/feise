<?php

namespace App\Http\Controllers\Admin;

use App\Model\GiftTokenSetting;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GiftTokenSettingController extends Controller
{
    public function show()
    {
        $data['giftTokenSettings']=GiftTokenSetting::all()->toArray();
        return view('admin.setting.gift_token_setting',$data);
    }

    public function update(Request $requests,$id)
    {
        $params=$requests->all();
        $giftTokenSetting=GiftTokenSetting::find($id);
        if($giftTokenSetting==null){
            $ret['meta']['code']=0;
            $ret['meta']['error']='目标不存在';
        }else{
            $giftTokenSetting->status=$params['status'];
            $giftTokenSetting->save();
            $ret['meta']['code']=1;
        }
        echo json_encode($ret);
    }
}

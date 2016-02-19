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

    public function detail($id)
    {
        $giftToken=GiftTokenSetting::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$giftToken->toArray();
        echo json_encode($ret);
    }

    public function update(Request $requests,$id)
    {
        $params=$requests->all();
        $giftTokenSetting=GiftTokenSetting::find($id);
        if($giftTokenSetting!=null){
            $giftTokenSetting->sum=$params['sum'];
            $giftTokenSetting->status=$params['status'];
            $giftTokenSetting->save();
            $ret['meta']['code']=1;
        }
        return redirect()->action('Admin\GiftTokenSettingController@show');
    }
}

<?php

namespace App\Http\Controllers;
require_once "../vendor/weixin_sdk/WxNotifyDeal.php";
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

}

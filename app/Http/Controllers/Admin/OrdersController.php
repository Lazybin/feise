<?php

namespace App\Http\Controllers\Admin;

use App\Model\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.orders.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $total=Order::count();

        $order=Order::skip($start)->take($length)->orderBy('id','desc');
        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $order->get()->toArray()
        ));
    }

    public function update(Request $requests,$id)
    {
        $params=$requests->all();
        $order=Order::where('out_trade_no',$id)->first();
        if($order==null){
            $ret['meta']['code']=0;
            $ret['meta']['error']='目标不存在';
        }else{
            $order->status=$params['status'];
            if($order->status==3){
                $order->shipments_time=date("Y-m-d H:i:s",time());
            }
            $order->save();
            $ret['meta']['code']=1;
        }
        echo json_encode($ret);
    }
}

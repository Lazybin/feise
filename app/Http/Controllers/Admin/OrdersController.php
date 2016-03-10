<?php

namespace App\Http\Controllers\Admin;

use App\Model\Order;
use App\Model\OrderGoods;
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

        $presell=$request->input('presell');
        if($presell!=0){
            $order=OrderGoods::join('orders','order_goods.order_id','=','orders.id')
                ->join('goods','order_goods.goods_id','=','goods.id')
                ->where('goods.is_presell','=',1)
                ->orderBy('orders.is_remind','desc')->orderBy('orders.id','desc')->groupBy('order_goods.order_id')
                ->select('orders.id','orders.out_trade_no','orders.user_id','orders.consignee','orders.shipping_address','orders.mobile','orders.total_fee','orders.status','orders.is_remind');
        }else{
            $order=Order::orderBy('orders.is_remind','desc')->orderBy('orders.id','desc')
                ->select('orders.id','orders.out_trade_no','orders.user_id','orders.consignee','orders.shipping_address','orders.mobile','orders.total_fee','orders.status','orders.is_remind');;
        }

        $out_trade_no=$request->input('number');
        if($out_trade_no!=null&&$out_trade_no!=''){
            $order=$order->where('orders.out_trade_no', '=',$out_trade_no);
        }

        $status=$request->input('status');
        if($status!=-1){
            if($status==5){
                $order=$order->where(function($query)
                {
                    $query->where('orders.status','=',5)->orWhere('orders.status','=',6);
                });
            }else{
                $order=$order->where('orders.status','=',$status);
            }
        }



        $total=$order->count();

        $order=$order->skip($start)->take($length);
        $orders=$order->get();
        $arrOrdersList=[];
        foreach($orders as $g){
            $arrGoods['id']=$g->id;
            $arrGoods['out_trade_no']=$g->out_trade_no;
            $arrGoods['user_id']=$g->user_id;
            $arrGoods['consignee']=$g->consignee;
            $arrGoods['shipping_address']=$g->shipping_address;
            $arrGoods['mobile']=$g->mobile;
            $arrGoods['total_fee']=$g->total_fee;
            $arrGoods['status']=$g->status;
            $arrGoods['is_remind']=$g->is_remind;
            //$arrGoods['express_fee']=$g->express_fee;
            $arrOrdersList[]=$arrGoods;
        }
        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $arrOrdersList
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
                $order->express_company_name=$params['express_company_name'];
                $order->express_number=$params['express_number'];
                $order->shipments_time=date("Y-m-d H:i:s",time());
            }
            $order->save();
            $ret['meta']['code']=1;
        }
        echo json_encode($ret);
    }

    public function detail($id){
        $order=Order::find($id)->toArray();

        $ret['meta']['code']=1;
        $ret['meta']['data']=$order;
        echo json_encode($ret);

    }
}

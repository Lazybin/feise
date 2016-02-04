<?php

namespace App\Http\Controllers\Admin;

use App\Model\ConversionGoods;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConversionGoodsController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.conversion_goods.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $total=ConversionGoods::count();
        $activityClassification=ConversionGoods::skip($start)->take($length)->orderBy('id','desc');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $activityClassification->get()->toArray()
        ));
    }

    public function detail($id)
    {
        $category=ConversionGoods::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$category->toArray();
        echo json_encode($ret);
    }
    public function store(Request $request)
    {
        $params=$request->all();
        $conversionGoods=new ConversionGoods();

        $goods_id=$params['goods_id'];

        $conversionGoods->goods_id=$goods_id;
        $conversionGoods->save();

        return redirect()->action('Admin\ConversionGoodsController@show');
    }

    public function update(Request $request,$id)
    {
        $params=$request->all();
        $conversionGoods=ConversionGoods::find($id);
        if($conversionGoods!=null){
            $goods_id=$params['goods_id'];

            $conversionGoods->goods_id=$goods_id;
            $conversionGoods->save();
        }
        return redirect()->action('Admin\ConversionGoodsController@show');
    }

    public function delete($id)
    {
        ConversionGoods::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

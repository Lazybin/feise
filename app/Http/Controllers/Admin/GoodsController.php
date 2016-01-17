<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Model\CategoryProperty;
use App\Model\Goods;
use App\Model\GoodsCategoryProperty;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    public function show()
    {
        $data['categories']=Category::where('pid',0)->get();
        return view('admin.goods.index',$data);
    }
    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);


        $goods=Goods::skip($start)->take($length)->orderBy('id','desc');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval(Goods::count()),
            "recordsFiltered" => intval(Goods::count()),
            "data"            => $goods->get()->toArray()
        ));
    }

    public function store(Request $request)
    {
        $params=$request->all();
        $properties=[];
        foreach ($params as $k=>$v){
            $pos=strpos($k,'property_');
            if($pos!==false){
                $id=substr($k,strlen('property_'),strlen($k));
                if(is_numeric($id))
                {
                    $properties[$id]=$v;
                    unset($params[$k]);
                }
            }
        }
        $params['category_id']=$params['category'];
        unset($params['category']);
        $goods=Goods::create($params);
        foreach($properties as $key=>$value){
            $arr=explode(',',$value);
            foreach($arr as $i){
                GoodsCategoryProperty::create([
                    'category_property_id'=>$key,
                    'goods_id'=>$goods->id,
                    'value'=>$i
                ]);
            }
        }
        return redirect()->action('Admin\GoodsController@show');
    }

    public function delete($id)
    {
        Goods::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

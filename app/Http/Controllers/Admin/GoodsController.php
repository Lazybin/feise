<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Model\CategoryProperty;
use App\Model\Goods;
use App\Model\GoodsCategoryProperty;
use App\Model\GoodsImages;
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

        $images=$params['images'];
        unset($params['images']);
        unset($params['uploadImages']);


        if ($request->hasFile('coverImage'))
        {
            $file = $request->file('coverImage');
            $fileName=time().'.'.$file->getClientOriginalExtension();
            $file->move(base_path().'/public/upload',$fileName);


            $params['cover']='/upload/'.$fileName;
        }
        unset($params['coverImage']);


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
        $len=strlen($images);
        if($len>0){
            $images=substr($images,0,$len-1);
            $images=explode(',',$images);
            foreach($images as $i){
                $goodsImage=new GoodsImages();
                $goodsImage->goods_id=$goods->id;
                $goodsImage->image_id=$i;
                $goodsImage->save();
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

    public function detail($id)
    {
        $goods=Goods::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$goods;
        echo json_encode($ret);
    }

    public function update(Request $requests,$id)
    {
        $params=$requests->all();
        $goods=Goods::find($id);
        if($goods!=null){
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
            unset($params['_token']);

            $images=$params['images'];
            unset($params['images']);

            unset($params['uploadImages']);

            if ($requests->hasFile('coverImage'))
            {
                $file = $requests->file('coverImage');
                $fileName=time().'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $params['cover']='/upload/'.$fileName;
            }
            unset($params['coverImage']);

            foreach($params as $n=>$p){
                $goods->$n=$p;
            }
            $goods->save();

            GoodsCategoryProperty::where('goods_id',$goods->id)->delete();
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

            GoodsImages::where('goods_id',$goods->id)->delete();
            $len=strlen($images);
            if($len>0){
                $images=substr($images,0,$len-1);
                $images=explode(',',$images);
                foreach($images as $i){
                    $goodsImage=new GoodsImages();
                    $goodsImage->goods_id=$goods->id;
                    $goodsImage->image_id=$i;
                    $goodsImage->save();
                }
            }
        }
        return redirect()->action('Admin\GoodsController@show');
    }
}

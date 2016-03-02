<?php

namespace App\Http\Controllers\Admin;

use App\Model\ActivityClassification;
use App\Model\ActivityClassificationGoods;
use App\Model\BannerGoods;
use App\Model\Category;
use App\Model\CategoryProperty;
use App\Model\ConversionGoods;
use App\Model\FreePost;
use App\Model\FreePostGoods;
use App\Model\Goods;
use App\Model\GoodsCategoryProperty;
use App\Model\GoodsImages;
use App\Model\HomeButtonGoods;
use App\Model\ThemeGoods;
use App\Model\Themes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    public function show()
    {
        $data['categories']=Category::where('pid',0)->get();
        $data['activity_classification']=ActivityClassification::all();
        $data['free_post']=FreePost::all();
        return view('admin.goods.index',$data);
    }
    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $category_id=$request->input('category_id');

        if($category_id!=null){
            $goods=Goods::where('category_id',$category_id);
            $count=$goods->count();
            $goods->skip($start)->take($length)->orderBy('id','desc');
        }else{
            $count=Goods::count();
            $goods=Goods::skip($start)->take($length)->orderBy('id','desc');
        }


        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($count),
            "recordsFiltered" => intval($count),
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
            $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move(base_path().'/public/upload',$fileName);


            $params['cover']='/upload/'.$fileName;
        }
        unset($params['coverImage']);

        if ($request->hasFile('evaluationPersonImage'))
        {
            $file = $request->file('evaluationPersonImage');
            $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move(base_path().'/public/upload',$fileName);


            $params['evaluation_person_image']='/upload/'.$fileName;
        }
        unset($params['evaluationPersonImage']);

        $params['goods_description']=$params['description'];
        unset($params['description']);

        $activityClassification=$params['activityClassification'];
        unset($params['activityClassification']);

        $freePost=$params['freePost'];
        unset($params['freePost']);


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

        if($activityClassification!=-1){
            ActivityClassificationGoods::create([
                'activity_classification_id'=>$activityClassification,
                'goods_id'=>$goods->id
            ]);
        }

        if($freePost!=-1){
            FreePostGoods::create([
                'free_posts_id'=>$freePost,
                'goods_id'=>$goods->id
            ]);
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
        $goods=Goods::find($id)->toArray();
        $activityClassificationGoods=ActivityClassificationGoods::where('goods_id',$id)->select('activity_classification_id')->first();
        if($activityClassificationGoods==null){
            $goods['activityClassification']=-1;
        }else{
            $goods['activityClassification']=$activityClassificationGoods->activity_classification_id;
        }


        $freePost=FreePostGoods::where('goods_id',$id)->select('free_posts_id')->first();
        if($freePost==null){
            $goods['freePost']=-1;
        }else{
            $goods['freePost']=$freePost->free_posts_id;
        }

        $ret['meta']['code']=1;
        $ret['meta']['data']=$goods;
        echo json_encode($ret);
    }
    public function updatePutaway(Request $requests)
    {
        $id=$requests->input('id');
        $status=$requests->input('status');
        if($status==0){
            $hasUse=$this->hasUse($id);
            if($hasUse!=1){
                $ret['meta']['code']=0;
                $ret['meta']['error']='该商品已在使用中，不能下架';
                echo json_encode($ret);
                return;
            }
        }
        $goods=Goods::find($id);
        $goods->is_putaway=$status;
        $goods->save();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }

    private function hasUse($id){
        if(ThemeGoods::where('goods_id',$id)->count()>0){
            return -1;
        }
        if(BannerGoods::where('goods_id',$id)->count()>0){
            return -2;
        }
        if(ActivityClassificationGoods::where('goods_id',$id)->count()>0){
            return -3;
        }
        if(ConversionGoods::where('goods_id',$id)->count()>0){
            return -4;
        }
        if(FreePostGoods::where('goods_id',$id)->count()>0){
            return -5;
        }
        if(HomeButtonGoods::where('goods_id',$id)->count()>0){
            return -6;
        }
        return 1;
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

            if ($requests->hasFile('evaluationPersonImage'))
            {
                $file = $requests->file('evaluationPersonImage');
                $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $params['evaluation_person_image']='/upload/'.$fileName;
            }
            unset($params['evaluationPersonImage']);

            $params['goods_description']=$params['description'];
            unset($params['description']);

            $activityClassification=$params['activityClassification'];
            unset($params['activityClassification']);

            $freePost=$params['freePost'];
            unset($params['freePost']);

            foreach($params as $n=>$p){
                $goods->$n=$p;
            }
            $goods->save();

            ActivityClassificationGoods::where('goods_id',$goods->id)->delete();
            if($activityClassification!=-1){
                ActivityClassificationGoods::create([
                    'activity_classification_id'=>$activityClassification,
                    'goods_id'=>$goods->id
                ]);
            }

            FreePostGoods::where('goods_id',$goods->id)->delete();
            if($freePost!=-1){
                FreePostGoods::create([
                    'free_posts_id'=>$freePost,
                    'goods_id'=>$goods->id
                ]);
            }



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

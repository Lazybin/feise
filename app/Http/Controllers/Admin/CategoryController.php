<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Model\CategoryProperty;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function show(Request $request)
    {
        $data['pid']=$request->input('pid',0);
        return view('admin.category.index',$data);
    }

    public function index(Request $request)
    {
        $pid=$request->input('pid',0);
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $operators=Category::where('pid',$pid);
        $total=$operators->count();
        $operators=$operators->skip($start)->take($length)->orderBy('sort')->orderBy('id');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $operators->get()->toArray()
        ));
    }

    public function store(Request $requests)
    {
        $params=$requests->all();
        $category=Category::create($params);
        if($params['pid']!=0)
            $this->createDefaultCategoryProperty($category->id);
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }

    private function createDefaultCategoryProperty($category_id)
    {
        CategoryProperty::create([
            'category_id'=>$category_id,
            'name'=>'颜色',
            'type'=>0
        ]);
        CategoryProperty::create([
            'category_id'=>$category_id,
            'name'=>'尺码',
            'type'=>0
        ]);
        CategoryProperty::create([
            'category_id'=>$category_id,
            'name'=>'数量',
            'type'=>1
        ]);
    }

    public function detail($id)
    {
        $category=Category::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$category->toArray();
        echo json_encode($ret);
    }

    public function update(Request $requests,$id)
    {
        $params=$requests->all();
        $category=Category::find($id);
        if($category==null){
            $ret['meta']['code']=0;
            $ret['meta']['error']='目标不存在';
        }else{
            $category->name=$params['name'];
            $category->sort=$params['sort'];
            $category->save();
            $ret['meta']['code']=1;
        }
        echo json_encode($ret);
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        Category::where('pid',$id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }

    public function get_property(Request $request,$category_id)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $categoryProperties=CategoryProperty::where('category_id',$category_id);
        $total=$categoryProperties->count();
        $categoryProperties=$categoryProperties->skip($start)->take($length)->orderBy('id');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $categoryProperties->get()->toArray()
        ));

    }

    public function delete_property($id)
    {
        CategoryProperty::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }

    public function store_property(Request $requests)
    {
        $params=$requests->all();
        $category=CategoryProperty::create($params);

        $ret['meta']['code']=1;
        echo json_encode($ret);
    }

    public function property_detail($id)
    {
        $categoryProperty=CategoryProperty::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$categoryProperty->toArray();
        echo json_encode($ret);
    }

    public function property_update(Request $requests,$id)
    {
        $params=$requests->all();
        $categoryProperty=CategoryProperty::find($id);
        if($categoryProperty==null){
            $ret['meta']['code']=0;
            $ret['meta']['error']='目标不存在';
        }else{
            $categoryProperty->name=$params['name'];
            $categoryProperty->type=$params['type'];
            $categoryProperty->save();
            $ret['meta']['code']=1;
        }
        echo json_encode($ret);
    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Model\ActivityClassification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ActivityClassificationsController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.activity_classification.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $total=ActivityClassification::count();
        $activityClassification=ActivityClassification::skip($start)->take($length)->orderBy('sort')->orderBy('id');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $activityClassification->get()->toArray()
        ));
    }

    public function store(Request $requests)
    {
        $params=$requests->all();
        ActivityClassification::create($params);
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }

    public function detail($id)
    {
        $category=ActivityClassification::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$category->toArray();
        echo json_encode($ret);
    }

    public function update(Request $requests,$id)
    {
        $params=$requests->all();
        $category=ActivityClassification::find($id);
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
        ActivityClassification::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

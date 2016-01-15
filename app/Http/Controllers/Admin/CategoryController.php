<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
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
        $operators=$operators->skip($start)->take($length)->orderBy('id','desc');

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
        Category::create($params);
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

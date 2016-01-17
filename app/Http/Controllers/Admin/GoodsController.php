<?php

namespace App\Http\Controllers\Admin;

use App\Model\Goods;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    public function show()
    {
        return view('admin.goods.index');
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
        echo '<pre>';var_dump($params);echo '</pre>';
//        $banner=new Banner();
//        $banner->title=$params['title'];
//        $banner->order=$params['order'];
//
//        $banner->action=$params['action'];
//
//        $file = $request->file('input-id');
//
//        if ($file->isValid())
//        {
//            $fileName=time().'.'.$file->getClientOriginalExtension();
//            $file->move(base_path().'/public/upload',$fileName);
//
//            $banner->path='/upload/'.$fileName;
//        }
//        $banner->save();
//        return redirect()->action('Admin\BannerController@show');
    }
}

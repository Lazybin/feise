<?php

namespace App\Http\Controllers\Admin;

use App\Model\ActivityClassificationGoods;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ActivityGoodsController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.activity_goods.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $total=ActivityClassificationGoods::count();
        $activityClassification=ActivityClassificationGoods::skip($start)->take($length)->orderBy('id','desc');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $activityClassification->get()->toArray()
        ));
    }
    public function delete($id)
    {
        ActivityClassificationGoods::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

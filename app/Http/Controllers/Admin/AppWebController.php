<?php

namespace App\Http\Controllers\Admin;

use App\Model\AppWeb;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class AppWebController extends Controller
{
    public function show()
    {
        return view('admin.app_web.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $total=AppWeb::count();
        $appWeb=AppWeb::skip($start)->take($length)->orderBy('id');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $appWeb->get()->toArray()
        ));
    }

    public function detail($id)
    {
        $category=AppWeb::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$category->toArray();
        echo json_encode($ret);
    }

    public function update(Request $request,$id)
    {
        $params=$request->all();
        $homeNavigation=AppWeb::find($id);

        if($homeNavigation!=null){
            if ($request->hasFile('contentImage'))
            {
                $file = $request->file('contentImage');
                $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $homeNavigation->pic='/upload/'.$fileName;
            }
            $homeNavigation->save();
        }
        return redirect()->action('Admin\AppWebController@show');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Model\Home;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeManageController extends Controller
{
    public function show()
    {
        return view('admin.home_manage.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);


        $subjects=Home::skip($start)->take($length)->orderBy('sort')->orderBy('id','desc');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval(Home::count()),
            "recordsFiltered" => intval(Home::count()),
            "data"            => $subjects->get()->toArray()
        ));
    }

    public function store(Request $request)
    {
        $params=$request->all();
        //var_dump($params);exit;
        Home::create($params);
        return redirect()->action('Admin\HomeManageController@show');
    }

    public function detail($id)
    {
        $home=Home::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$home;
        echo json_encode($ret);
    }

    public function update(Request $requests,$id)
    {
        $params=$requests->all();
        $home=Home::find($id);
        if($home!=null){
            $home->item_id=$params['item_id'];
            $home->type=$params['type'];
            $home->sort=$params['sort'];
            $home->save();
        }
        return redirect()->action('Admin\HomeManageController@show');
    }

    public function delete($id)
    {
        Home::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Model\UserLevel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserLevelsController extends Controller
{
    public function show()
    {
        return view('admin.setting.user_level');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $levels=UserLevel::skip($start)->take($length)->orderBy('id');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval(UserLevel::count()),
            "recordsFiltered" => intval(UserLevel::count()),
            "data"            => $levels->get()->toArray()
        ));
    }

    public function store(Request $request)
    {
        $params=$request->all();
        $level=new UserLevel();
        $level->name=$params['name'];
        $level->sum_lowest=$params['sum_lowest'];
        $level->sum_highest=$params['sum_highest'];
        $level->save();
        return redirect()->action('Admin\UserLevelsController@show');
    }

    public function detail($id)
    {
        $level=UserLevel::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$level->toArray();
        echo json_encode($ret);
    }

    public function update(Request $request,$id)
    {
        $params=$request->all();
        $level=UserLevel::find($id);
        if($level!=null){
            $level->name=$params['name'];
            $level->sum_lowest=$params['sum_lowest'];
            $level->sum_highest=$params['sum_highest'];
            $level->save();
        }
        return redirect()->action('Admin\UserLevelsController@show');
    }

    public function delete($id)
    {
        UserLevel::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

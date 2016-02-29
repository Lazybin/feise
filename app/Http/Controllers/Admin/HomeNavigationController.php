<?php

namespace App\Http\Controllers\Admin;

use App\Model\HomeButtonGoods;
use App\Model\HomeNavigation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeNavigationController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.home_navigation.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $total=HomeNavigation::count();
        $activityClassification=HomeNavigation::skip($start)->take($length)->orderBy('sort')->orderBy('id');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $activityClassification->get()->toArray()
        ));
    }

    public function detail($id)
    {
        $category=HomeNavigation::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$category->toArray();
        echo json_encode($ret);
    }

    public function update(Request $request,$id)
    {
        $params=$request->all();
        $homeNavigation=HomeNavigation::find($id);

        if($homeNavigation!=null){
            if($homeNavigation->id==1){
                $homeNavigation->title=$params['titleNew'];
                $homeNavigation->subhead=$params['subheadNew'];
                $homeNavigation->save();

                HomeButtonGoods::where('home_button_id',$homeNavigation->id)->delete();
                $items=substr($params['item_id'],0,strlen($params['item_id'])-1);
                $items=explode(',',$items);
                foreach($items as $v){
                    $homeButtonGoods=new HomeButtonGoods();
                    $homeButtonGoods->home_button_id=$homeNavigation->id;
                    $homeButtonGoods->goods_id=$v;
                    $homeButtonGoods->save();
                }
                return redirect()->action('Admin\HomeNavigationController@show');
            }
            if($params['type']==0){
                unset($params['action']);
                if ($request->hasFile('coverImage'))
                {
                    $file = $request->file('coverImage');
                    $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                    $file->move(base_path().'/public/upload',$fileName);


                    $params['path']='/upload/'.$fileName;
                }

            }
            unset($params['coverImage']);
            unset($params['_token']);


            foreach($params as $n=>$p){
                $homeNavigation->$n=$p;
            }
            $homeNavigation->save();
        }
        return redirect()->action('Admin\HomeNavigationController@show');
    }
}

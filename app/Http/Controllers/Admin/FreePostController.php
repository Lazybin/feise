<?php

namespace App\Http\Controllers\Admin;

use App\Model\FreePost;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FreePostController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.free_post.index');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $total=FreePost::count();
        $activityClassification=FreePost::skip($start)->take($length)->orderBy('sort')->orderBy('id');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval($total),
            "recordsFiltered" => intval($total),
            "data"            => $activityClassification->get()->toArray()
        ));
    }

    public function detail($id)
    {
        $category=FreePost::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$category->toArray();
        echo json_encode($ret);
    }

    public function update(Request $request,$id)
    {
        $params=$request->all();
        $freepost=FreePost::find($id);
        if($freepost!=null){
            if ($request->hasFile('coverImage'))
            {
                $file = $request->file('coverImage');
                $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $params['cover']='/upload/'.$fileName;
            }
            unset($params['coverImage']);

            if ($request->hasFile('headImage'))
            {
                $file = $request->file('headImage');
                $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $params['head_image']='/upload/'.$fileName;
            }
            unset($params['headImage']);
            unset($params['_token']);



            foreach($params as $n=>$p){
                $freepost->$n=$p;
            }
            $freepost->save();
        }
        return redirect()->action('Admin\FreePostController@show');
    }
}

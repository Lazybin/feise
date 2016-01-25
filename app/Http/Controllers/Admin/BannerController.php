<?php

namespace App\Http\Controllers\Admin;

use App\Model\Banner;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    //
    public function show()
    {
        return view('admin.setting.banner');
    }

    public function index(Request $request)
    {
        $start=$request->input('start', 0);
        $length=$request->input('length', 5);
        $draw=$request->input('draw', 1);

        $banners=Banner::skip($start)->take($length)->orderBy('order');

        echo json_encode(array(
            "draw"            => intval( $draw ),
            "recordsTotal"    => intval(Banner::count()),
            "recordsFiltered" => intval(Banner::count()),
            "data"            => $banners->get()->toArray()
        ));
    }

    public function detail($id)
    {
        $banner=Banner::find($id);
        $ret['meta']['code']=1;
        $ret['meta']['data']=$banner->toArray();
        echo json_encode($ret);
    }


    public function store(Request $request)
    {
        $params=$request->all();
        $banner=new Banner();
        $banner->title=$params['title'];
        $banner->order=$params['order'];

        $banner->action=$params['action'];

        $banner->banner_position=$params['banner_position'];

        if ($request->hasFile('coverImage'))
        {
            $file = $request->file('coverImage');
            $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move(base_path().'/public/upload',$fileName);


            $banner->path='/upload/'.$fileName;
        }

        if ($request->hasFile('detailImage'))
        {
            $file = $request->file('detailImage');
            $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move(base_path().'/public/upload',$fileName);


            $banner->detail_image='/upload/'.$fileName;
        }


        $banner->save();
        return redirect()->action('Admin\BannerController@show');
    }

    public function update(Request $request,$id)
    {
        $params=$request->all();
        $banner=Banner::find($id);
        if($banner!=null){
            $banner->title=$params['title'];
            $banner->order=$params['order'];
            $banner->banner_position=$params['banner_position'];
            $banner->action=$params['action'];

            if ($request->hasFile('coverImage'))
            {
                $file = $request->file('coverImage');
                $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $banner->path='/upload/'.$fileName;
            }

            if ($request->hasFile('detailImage'))
            {
                $file = $request->file('detailImage');
                $fileName=md5(uniqid()).'.'.$file->getClientOriginalExtension();
                $file->move(base_path().'/public/upload',$fileName);


                $banner->detail_image='/upload/'.$fileName;
            }
            $banner->save();
        }
        return redirect()->action('Admin\BannerController@show');
    }

    public function delete($id)
    {
        Banner::find($id)->delete();
        $ret['meta']['code']=1;
        echo json_encode($ret);
    }
}

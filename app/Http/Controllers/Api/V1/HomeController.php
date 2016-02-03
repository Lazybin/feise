<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Collection;
use App\Model\Home;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/home",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class HomeController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/home",
     *   description="首页",
     *   @SWG\Operation(
     *     method="GET", summary="获得首页列表", notes="获得首页列表",
     *     type="Home",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="PageNum",
     *         description="分页开始位置",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=1
     *     ),@SWG\Parameter(
     *         name="PerPage",
     *         description="取得长度",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=10
     *     )
     *
     *   )
     * )
     */
    public function index(Request $request)
    {
        $start=$request->input('PageNum', 0);
        $length=$request->input('PerPage', 5);
        $start=($start-1)*$length;
        $response=new BaseResponse();
        $home=Home::skip($start)->take($length)->orderBy('sort')->orderBy('id','desc');
        $response->rows=$home->get();
        $response->total=Home::count();
        return $response->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     *
     * @SWG\Api(
     *   path="/home/{id}",
     *   description="获取首页项详情",
     *   @SWG\Operation(
     *     method="GET", summary="获得主题详情", notes="获得主题详情",
     *     type="Home",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     ),@SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=-1
     *     ),
     *
     *   )
     * )
     */
    public function show(Request $request,$id)
    {
        //
        $response=new BaseResponse();
        $user_id=$request->input('user_id',-1);
        $home=Home::find($id)->toArray();
        if($home['type']==1){
            $home['has_collection']=0;
            if($user_id!=-1){
                $collection=Collection::where('user_id',$user_id)->where('type',1)->where('item_id',$home['item_id'])->first();
                if($collection!=null){
                    $home['has_collection']=1;
                }
            }
        }
        $response->Data=$home;
        return $response->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

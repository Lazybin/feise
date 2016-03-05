<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Collection;
use App\Model\Goods;
use App\Model\UserComment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/goods",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     *   path="/goods/{id}",
     *   description="商品",
     *   @SWG\Operation(
     *     method="GET", summary="获得商品详情", notes="获得商品详情",
     *     type="Goods",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="商品id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=-1
     *     )
     *
     *   )
     * )
     */
    public function show(Request $request,$id)
    {
        $user_id=$request->input('user_id',-1);
        $response=new BaseResponse();
        $theme=Goods::find($id);
        $theme['has_collection']=0;
        if($user_id!=-1){
            $c=Collection::where('user_id',$user_id)->where('type',0)->where('item_id',$theme['id'])->first();
            if($c!=null){
                $theme['has_collection']=1;
            }
        }
        $comments=UserComment::select('user_comments.*','user_infos.nick_name','user_infos.head_icon')->leftJoin('user_infos','user_infos.id','=','user_comments.user_id')->where('type',0)->where('item_id',$theme['id']);
        $rows=$comments->skip(0)->take(10)->orderBy('id','desc')->get()->toArray();

        $theme['comments']=$rows;
        $response->Data=$theme;
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
     *
     * @SWG\Api(
     *   path="/goods/{id}",
     *   @SWG\Operation(
     *     method="PUT", summary="更新分享次数", notes="更新分享次数",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="商品id",
     *         paramType="path",
     *         required=true,
     *         type="string"
     *     )
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        $response=new BaseResponse();
        $goods=Goods::find($id);
        if($goods!=null){
            $goods->share_times=$goods->share_times+1;
            $goods->save();
        }
        return $response->toJson();
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

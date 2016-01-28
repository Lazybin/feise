<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\FreePost;
use App\Model\FreePostGoods;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Model(
 * id="FreePostDetail",
 *  @SWG\Property(name="free_post",type="FreePost",description="分类详情"),
 *  @SWG\Property(name="goods_list",type="array",@SWG\Items("FreePostGoods"),description="商品列表"),
 * )
 */
/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/free_post",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class FreePostController extends Controller
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
     *   path="/free_post/{id}",
     *   description="包邮分类详情",
     *   @SWG\Operation(
     *     method="GET", summary="获得包邮分类详情", notes="获得包邮分类详情",
     *     type="FreePostDetail",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="分类id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *   )
     * )
     */
    public function show(Request $request,$id)
    {
        $response=new BaseResponse();
        $freePost=FreePost::find($id);
        $goods_list=FreePostGoods::where('free_posts_id',$id);

        $goods_list=$goods_list->orderBy('id','desc')->get();
        $ret=(object)null;
        $ret->free_post=$freePost;
        $ret->goods_list=$goods_list;
        $response->Data=$ret;
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

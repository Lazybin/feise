<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\UserComment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/comments",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class UserCommentsController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/comments",
     *   description="评论（新20160216）",
     *   @SWG\Operation(
     *     method="GET", summary="评论列表", notes="评论列表",
     *     type="UserComment",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="type",
     *         description="类型，0---》商品 1---》主题",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     ),@SWG\Parameter(
     *         name="item_id",
     *         description="项id",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     ),
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
     *   )
     * )
     */
    public function index(Request $request)
    {
        $start=$request->input('PageNum', 0);
        $length=$request->input('PerPage', 10);
        $type=$request->input('type');
        $item_id=$request->input('item_id');
        $start=($start-1)*$length;
        $response=new BaseResponse();

        $comments=UserComment::where('type',$type)->where('item_id',$item_id);
        $rows=$comments->skip($start)->take($length)->orderBy('id','desc')->get()->toArray();
        $response->rows=$rows;
        $response->total=$comments->count();
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
     *
     * @SWG\Api(
     *   path="/comments",
     *   @SWG\Operation(
     *     method="POST", summary="新增评论", notes="新增评论",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="comments_info",
     *         description="提交的评论信息",
     *         paramType="body",
     *         required=true,
     *         type="newComment"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        UserComment::create((array)$content);
        return $response->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     *
     * @SWG\Api(
     *   path="/comments/{id}",
     *   @SWG\Operation(
     *     method="DELETE", summary="删除评论", notes="删除评论",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="评论id",
     *         paramType="path",
     *         required=true,
     *         type="integer"
     *     )
     *   )
     * )
     */
    public function destroy($id)
    {
        $response=new BaseResponse();
        $comment=UserComment::find($id);
        if($comment!=null){
            $comment->delete();
        }
        return $response->toJson();
    }
}

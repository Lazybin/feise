<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * @SWG\Model(
 * id="newCollection",
 * @SWG\Property(name="user_id",type="integer",description="用户id"),
 * @SWG\Property(name="type",type="integer",description="类型，0---》商品 1---》主题"),
 * @SWG\Property(name="item_id",type="integer",description="项id"),
 * @SWG\Property(name="status",type="integer",description="操作类型，0--》删除，1---》新增")
 * )
 */

/**
 * @SWG\Model(
 * id="newCollectionList",
 * @SWG\Property(name="itemList",type="array",@SWG\Items("newCollection"),description="收藏列表")
 * )
 */
/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/collection",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class CollectionController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/collection",
     *   description="收藏",
     *   @SWG\Operation(
     *     method="GET", summary="获得用户收藏列表", notes="获得用户收藏列表",
     *     type="Collection",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     ),@SWG\Parameter(
     *         name="type",
     *         description="类型，0---》商品 1---》主题",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=0
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
     *
     *   )
     * )
     */
    public function index(Request $request)
    {
        $start=$request->input('PageNum', 0);
        $length=$request->input('PerPage', 5);
        $type=$request->input('type',0);
        $user_id=$request->input('user_id');
        $start=($start-1)*$length;
        $response=new BaseResponse();
        $collection=Collection::where('user_id',$user_id)->where('type',$type);

        $rows=$collection->skip($start)->take($length)->orderBy('id','desc')->get();
        $response->rows=$rows;
        $response->total=$collection->count();
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
     *   path="/collection",
     *   @SWG\Operation(
     *     method="POST", summary="添加/删除收藏", notes="添加/删除收藏",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="collection_info",
     *         description="提交的收藏信息",
     *         paramType="body",
     *         required=true,
     *         type="newCollection"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        if($content->status==1){
            $co=Collection::where('user_id',$content->user_id)
                ->where('type',$content->type)
                ->where('item_id',$content->item_id)->first();
            if($co==null){
                $collection=new Collection();
                $collection->user_id=$content->user_id;
                $collection->type=$content->type;
                $collection->item_id=$content->item_id;
                $collection->save();
            }
        }else{
            $response=new BaseResponse();
            $order=Collection::where('user_id',$content->user_id)->where('type',$content->type)->where('item_id',$content->item_id)->first();
            if($order!=null){
                $order->delete();
            }
        }

        return $response->toJson();
    }

    /**
     *
     * @SWG\Api(
     *   path="/collection/batch_store",
     *   @SWG\Operation(
     *     method="POST", summary="批量添加收藏", notes="批量添加收藏",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="collection_list",
     *         description="提交的收藏信息",
     *         paramType="body",
     *         required=true,
     *         type="newCollectionList"
     *     )
     *   )
     * )
     */
    public function batchStore(Request $request){
        DB::beginTransaction();
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        $itemList=$content->itemList;
        foreach ($itemList as $v){
            $co=Collection::where('user_id',$v->user_id)
                ->where('type',$v->type)
                ->where('item_id',$v->item_id)->first();
            if($co==null){
                $collection=new Collection();
                $collection->user_id=$v->user_id;
                $collection->type=$v->type;
                $collection->item_id=$v->item_id;
                $collection->save();
            }
        }
        DB::commit();
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


    public function destroy($id)
    {
        $response=new BaseResponse();
        $order=Collection::find($id);
        if($order!=null){
            $order->delete();
        }
        return $response->toJson();
    }
}

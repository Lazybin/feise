<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\ShippingAddress;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/shipping_address",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class ShippingAddressController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/shipping_address",
     *   description="收货地址",
     *   @SWG\Operation(
     *     method="GET", summary="收货地址列表", notes="收货地址列表",
     *     type="Area",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
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
        $user_id=$request->input('user_id');
        $start=($start-1)*$length;
        $response=new BaseResponse();
        $shippingAddress=ShippingAddress::where('user_id',$user_id);
        $rows=$shippingAddress->skip($start)->take($length)->orderBy('id','desc')->get()->toArray();
        $response->rows=$rows;
        $response->total=$shippingAddress->count();
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
     *   path="/shipping_address",
     *   @SWG\Operation(
     *     method="POST", summary="新增收货地址", notes="新增收货地址",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="address_info",
     *         description="提交的地址信息",
     *         paramType="body",
     *         required=true,
     *         type="newShippingAddress"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        ShippingAddress::create((array)$content);
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
     *
     * @SWG\Api(
     *   path="/shopping_cart/{id}",
     *   @SWG\Operation(
     *     method="PUT", summary="更新收货地址", notes="更新收货地址",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="收货地址ID",
     *         paramType="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="address_info",
     *         description="提交的地址信息",
     *         paramType="body",
     *         required=true,
     *         type="newShippingAddress"
     *     )
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        $response=new BaseResponse();
        $content=json_decode($request->getContent(false));
        if($id==null){
            $response->Code=BaseResponse::CODE_ERROR_CHECK;
            $response->Message='缺少参数';
            return $response->toJson();
        }
        $shippingAddress=new ShippingAddress();
        if($shippingAddress==null){
            $response->Code=BaseResponse::CODE_ERROR_BUSINESS;
            $response->Message='未找到对应项目';
            return $response->toJson();
        }
        $shippingAddress->user_id=$content['user_id'];
        $shippingAddress->province=$content['province'];
        $shippingAddress->city=$content['city'];
        $shippingAddress->district=$content['district'];
        $shippingAddress->detailed_address=$content['detailed_address'];
        $shippingAddress->save();
        return $response->toJson();
    }

    /**
     *
     * @SWG\Api(
     *   path="/shopping_cart/{id}",
     *   @SWG\Operation(
     *     method="DELETE", summary="删除地址", notes="删除地址",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="地址id",
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
        $shoppingCart=ShippingAddress::find($id);
        if($shoppingCart!=null){
            $shoppingCart->delete();
        }
        return $response->toJson();
    }
}

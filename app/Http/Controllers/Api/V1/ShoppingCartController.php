<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\ShoppingCart;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/shopping_cart",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class ShoppingCartController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/shopping_cart",
     *   description="购物车",
     *   @SWG\Operation(
     *     method="GET", summary="获得用户购物车列表", notes="获得用户购物车列表",
     *     type="ShoppingCart",
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
     *
     *   )
     * )
     */
    public function index(Request $request)
    {
        $response=new BaseResponse();
        $user_id=$request->input('user_id');
        $start=$request->input('PageNum', 1);
        $length=$request->input('PerPage', 5);

        $start=($start-1)*$length;

        if($user_id==null){
            $response->Code=BaseResponse::CODE_ERROR_CHECK;
            $response->Message='缺少参数';
        }else{
            $shoppingCart=ShoppingCart::where('user_id',$user_id);
            $count=$shoppingCart->count();
            $response->rows=$shoppingCart->get();
            $response->total=$count;
        }

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
     *   path="/shopping_cart",
     *   @SWG\Operation(
     *     method="POST", summary="添加到购物车", notes="添加到购物车",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="shopping_cart_info",
     *         description="提交的商品信息",
     *         paramType="body",
     *         required=true,
     *         type="newShoppingCartParams"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        $content->properties =json_encode($content->properties);

        ShoppingCart::create((array)$content);
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



    public function edit($id)
    {
        //
    }

    /**
     *
     * @SWG\Api(
     *   path="/shopping_cart/{id}",
     *   @SWG\Operation(
     *     method="PUT", summary="修改购物车", notes="修改购物车",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="shopping_cart_info",
     *         description="提交的商品信息",
     *         paramType="body",
     *         required=true,
     *         type="newShoppingCartParams"
     *     )
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));
        $content->properties =json_encode($content->properties);
        ShoppingCart::where('id',$id)->update((array)$content);
        return $response->toJson();
    }

    /**
     *
     * @SWG\Api(
     *   path="/shopping_cart/{id}",
     *   @SWG\Operation(
     *     method="DELETE", summary="删除购物车", notes="删除购物车",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     )
     *   )
     * )
     */
    public function destroy($id)
    {
        $response=new BaseResponse();
        ShoppingCart::find($id)->delete();
        return $response->toJson();
    }
}

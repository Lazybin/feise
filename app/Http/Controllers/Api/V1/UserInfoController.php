<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\UserInfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="1.0",
 *     swaggerVersion="1.2",
 *     resourcePath="/user_info",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class UserInfoController extends Controller
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
     *
     * @SWG\Api(
     *   path="/user_info",
     *   description="用户信息（新20160304）",
     *   @SWG\Operation(
     *     method="POST", summary="添加，修改用户信息", notes="添加，修改用户信息",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_info",
     *         description="用户id",
     *         paramType="body",
     *         required=true,
     *         type="UserInfo"
     *     )
     *   )
     * )
     */
    public function store(Request $request)
    {
        $response=new BaseResponse();
        $content = json_decode($request->getContent(false));

        if(!isset($content->id)){
            $response->Code=BaseResponse::CODE_ERROR_CHECK;
            $response->Message='用户id不能为空';
            return $response->toJson();
        }
        $userId=$content->id;
        $userAccount=$content->user_account;
        $nickName=$content->nick_name;
        $headIcon=$content->head_icon;
        $userInfo=UserInfo::findOrNew($userId);
        $userInfo->id=$userId;
        $userInfo->user_account=$userAccount;
        $userInfo->nick_name=$nickName;
        $userInfo->head_icon=$headIcon;
        $userInfo->save();
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

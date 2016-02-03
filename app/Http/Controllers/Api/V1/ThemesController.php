<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Collection;
use App\Model\Themes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/themes",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class ThemesController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/themes",
     *   description="主题",
     *   @SWG\Operation(
     *     method="GET", summary="获得主题列表", notes="获得主题列表",
     *     type="Themes",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=-1
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
        $start=($start-1)*$length;
        $user_id=$request->input('user_id',-1);
        $response=new BaseResponse();
        $themes=Themes::skip($start)->take($length)->orderBy('id','desc');
        $rows=$themes->get()->toArray();

        foreach($rows as &$v){
            $v['has_collection']=0;
            if($user_id!=-1){
                $collection=Collection::where('user_id',$user_id)->where('type',1)->where('id',$v['id'])->first();
                if($collection!=null){
                    $v['has_collection']=1;
                }
            }
        }
        $response->rows=$rows;
        $response->total=Themes::count();
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
     *   path="/themes/{id}",
     *   description="主题",
     *   @SWG\Operation(
     *     method="GET", summary="获得主题详情", notes="获得主题详情",
     *     type="Themes",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="主题id",
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
        $theme=Themes::find($id)->toArray();
        foreach($theme['goods'] as &$v){
            $v['has_collection']=0;
            if($user_id!=-1){
                $collection=Collection::where('user_id',$user_id)->where('type',0)->where('id',$v['id'])->first();
                if($collection!=null){
                    $v['has_collection']=1;
                }
            }
        }
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

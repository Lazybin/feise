<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Collection;
use App\Model\Home;
use App\Model\UserComment;
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
     *     ),@SWG\Parameter(
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
    public function index(Request $request)
    {
        $start=$request->input('PageNum', 0);
        $length=$request->input('PerPage', 5);
        $user_id=$request->input('user_id',-1);
        $start=($start-1)*$length;
        $response=new BaseResponse();
        $home=Home::skip($start)->take($length)->orderBy('sort')->orderBy('id','desc')->get()->toArray();

        foreach($home as &$v){
            $v['item']['has_collection']=0;
            if($user_id!=-1&&$v['type']==1){
                $collection=Collection::where('user_id',$user_id)->where('type',1)->where('item_id',$v['item']['id'])->first();
                if($collection!=null){
                    $v['item']['has_collection']=1;
                }
            }
        }

        $response->rows=$home;
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

            $comments=UserComment::select('user_comments.*','user_infos.nick_name','user_infos.head_icon')->leftJoin('user_infos','user_infos.id','=','user_comments.user_id')
                ->where('user_comments.type',1)
                ->where('user_comments.item_id',$home['item']['id']);
            $rows=$comments->skip(0)->take(10)->orderBy('id','desc')->get()->toArray();

            foreach($rows as &$v){
                //$v['']
                $sum=Order::select(DB::raw('SUM(total_fee) as total_pay'))->where('user_id',$v['user_id'])->where('status',4)->first()->toArray();
                if($sum==null||$sum['total_pay']==null){
                    $sum=0;
                }else{
                    $sum=$sum['total_pay'];
                }

                $level=UserLevel::where('sum_lowest','<=',$sum)->where('sum_highest','>',$sum)->first()->toArray();
                //var_dump($level);exit;
                $v['level']=$level['name'];
            }

            $home['comments']=$rows;
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

<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BaseResponse;
use App\Model\Collection;
use App\Model\Goods;
use App\Model\SearchRecords;
use App\Model\Themes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * @SWG\Resource(
 *     apiVersion="1.0",
 *     swaggerVersion="1.2",
 *     resourcePath="/search",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class SearchController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/search",
     *   description="搜索（新20160216）",
     *   @SWG\Operation(
     *     method="GET", summary="获得搜索列表", notes="获得搜索列表",
     *     type="array",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=-1
     *     ),@SWG\Parameter(
     *         name="device_token",
     *         description="设备号",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     ),@SWG\Parameter(
     *         name="type",
     *         description="搜索类型，0：商品，1：主题",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=0
     *     ),@SWG\Parameter(
     *         name="keywords",
     *         description="搜索关键字",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="string"
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
        $length=$request->input('PerPage', 10);
        $type=$request->input('type',0);//0--->商品 1--->主题
        $keywords=$request->input('keywords','');
        $user_id=$request->input('user_id',-1);
        $device_token=$request->input('device_token');
        $response=new BaseResponse();
        $start=($start-1)*$length;
        if($type==0){
            $goods=Goods::where('name', 'like', '%'.$keywords.'%')->orWhere('description','like', '%'.$keywords.'%');
            $total=$goods->count();
            $goods=$goods->skip($start)->take($length)->orderBy('id','desc');
            $rows=$goods->get()->toArray();
        }else{
            $themes=Themes::where('title', 'like', '%'.$keywords.'%')
                ->orWhere('subhead','like', '%'.$keywords.'%')
                ->orWhere('themes_description','like', '%'.$keywords.'%');
            $total=$themes->count();
            $themes=$themes->skip($start)->take($length)->orderBy('id','desc');
            $rows=$themes->get()->toArray();
        }

        foreach($rows as &$v){
            $v['has_collection']=0;
            if($user_id!=-1&&$user_id!=0){
                $collection=Collection::where('user_id',$user_id)->where('type',$type)->where('id',$v['id'])->first();
                if($collection!=null){
                    $v['has_collection']=1;
                }
            }
        }
        //搜索记录
        $searchRecords=new SearchRecords();
        $searchRecords->user_id=$user_id;
        $searchRecords->device_token=$device_token;
        $searchRecords->keywords=$keywords;
        $searchRecords->save();
        $response->rows=$rows;
        $response->total=$total;
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
     *   path="/search/get_search_records",
     *   @SWG\Operation(
     *     method="GET", summary="获得最近搜索记录", notes="获得最近搜索记录",
     *     type="SearchRecords",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="用户id",
     *         paramType="query",
     *         required=false,
     *         allowMultiple=false,
     *         type="integer",
     *         defaultValue=-1
     *     ),@SWG\Parameter(
     *         name="device_token",
     *         description="设备号",
     *         paramType="query",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer"
     *     )
     *   )
     * )
     */
    public function getSearchRecords(Request $request){
        $user_id=$request->input('user_id',-1);
        $device_token=$request->input('device_token');
        $response=new BaseResponse();
        $ret=[];
        $ret['hot']=SearchRecords::select(DB::raw('count(*) as search_times'),'keywords')
            ->groupBy('keywords')
            ->orderBy('search_times','desc')
            ->limit(8)->get();
        if($user_id!=-1&&$user_id!=0){
            $ret['self_last']=SearchRecords::where('user_id',$user_id)->select('keywords')
                ->groupBy('keywords')
                ->orderBy('created_at','desc')
                ->limit(4)->get();
        }else{
            $ret['self_last']=SearchRecords::where('device_token',$device_token)->select('keywords')
                ->groupBy('keywords')
                ->orderBy('created_at','desc')
                ->limit(4)->get();
        }
        $response->Data=$ret;
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

<?php

namespace App\Http\Controllers;

use App\Model\Banner;
use App\Model\Goods;
use App\Model\HomeNavigation;
use App\Model\NewYearActivity;
use App\Model\Themes;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/wap",
 *     basePath="http://120.27.199.121/feise/public/api/v1"
 * )
 */
class WapController extends Controller
{
    /**
     *
     * @SWG\Api(
     *   path="/wap/banner_detail/{id}",
     *   description="APP WAP 页",
     *   @SWG\Operation(
     *     method="GET", summary="banner详情wap", notes="banner详情wap",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="banner id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function bannerDetail($id){
        $data['content']=Banner::find($id)->toArray();
        return view('wap.banner_detail',$data);
    }

    /**
     *
     * @SWG\Api(
     *   path="/wap/goods_detail/{id}",
     *   @SWG\Operation(
     *     method="GET", summary="商品详情wap", notes="商品详情wap",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="商品 id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function goodsDetail($id){
        $data['goods']=Goods::find($id)->toArray();
        //echo $data['goods']['detailed_introduction'];exit;
        return view('wap.goods_detail',$data);
    }

    /**
     *
     * @SWG\Api(
     *   path="/wap/themes_description/{id}",
     *   @SWG\Operation(
     *     method="GET", summary="主题描述wap", notes="主题描述wap",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="主题id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function themesDescription($id){
        $data['themes']=Themes::find($id)->toArray();
        return view('wap.themes_description',$data);
    }

    /**
     *
     * @SWG\Api(
     *   path="/wap/home_navigation_detail/{id}",
     *   @SWG\Operation(
     *     method="GET", summary="导航按钮详情图片", notes="导航按钮详情图片",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="id",
     *         description="id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function homeNavigationDetail($id){
        $data['homeNavigation']=HomeNavigation::find($id)->toArray();
        return view('wap.home_navigation_detail',$data);
    }

    /**
     *
     * @SWG\Api(
     *   path="/wap/new_year_activity/{user_id}",
     *   @SWG\Operation(
     *     method="GET", summary="春节活动", notes="春节活动",
     *     @SWG\ResponseMessage(code=0, message="成功"),
     *     @SWG\Parameter(
     *         name="user_id",
     *         description="user_id 用户id",
     *         paramType="path",
     *         required=true,
     *         allowMultiple=false,
     *         type="integer",
     *     )
     *
     *   )
     * )
     */
    public function newYearActivity($user_id){
        $newYearTime=strtotime("2016-02-07 00:00:00");
        $endTime=strtotime("2016-02-15 00:00:00");
        $data['user_id']=$user_id;
        //$data['content']='运筹帷幄事业新';

        $contentList=[
            '运筹帷幄事业新',
            '祥瑞降喜爱如意',
            '大吉大利财运旺',
            '行远高升兄弟睦',
            '猴年大吉好运来',
            '年年有余家业丰',
            '吉庆安康身体健',

        ];
        $rand=rand(0,6);
        $data['content']=$contentList[$rand];

        if(time()<$newYearTime){
            return view('wap.new_year_index',$data);
        }else if(time()>=$newYearTime&&time()<=$endTime){
            $t = time();
            $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
            $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
            $times=NewYearActivity::where('user_id',$user_id)->count();
            $today=NewYearActivity::where('user_id',$user_id)->where('created_at','>=',date('Y-m-d H:i:s',$start))
                ->where('created_at','<=',date('Y-m-d H:i:s',$end))->first();
            if($today!=null){
                $data['conent']=$today->content;
                $data['times']=7-$times;
                return view('wap.new_year_activity_has_join',$data);
            }

            $data['times']=7-$times-1;
            return view('wap.new_year_activity',$data);
        }else{
            return null;
        }

    }
}

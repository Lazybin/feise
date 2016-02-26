<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Model(id="HomeNavigation")
 */
class HomeNavigation extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="title",type="string",description="标题")
     * @SWG\Property(name="subhead",type="string",description="副标题")
     * @SWG\Property(name="type",type="integer",description="类型，0--》图片，1---》网页,2--->春节活动")
     * @SWG\Property(name="path",type="string",description="图片路径")
     * @SWG\Property(name="sort",type="integer",description="排序")
     * @SWG\Property(name="action",type="string",description="网页跳转路径")
     * @SWG\Property(name="goods",description="当为新用户福利时有值",type="HomeButtonGoods")
     */
    protected $appends=['goods'];

    public function getGoodsAttribute()
    {
        //$goodsList=OrderGoods::with('goods')->where('order_id',$this->id)->get();
        $goodsList=HomeButtonGoods::where('home_button_id',$this->id)->get();
        if($goodsList == null) {
            return null;
        }
        return $goodsList->toArray();
    }
}

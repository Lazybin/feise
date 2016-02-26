<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="Banner")
 */
class Banner extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="banner_position",type="integer",description="图片位置 0--->首页 1--->优惠")
     * @SWG\Property(name="type",type="integer",description="类型 0--->主题 1--->专题 2---->活动 3--->春节活动 4--->商品合集")
     * @SWG\Property(name="title",type="string",description="标题")
     * @SWG\Property(name="path",type="string",description="路径，当type=2时有值")
     * @SWG\Property(name="detail_image",type="string",description="详情图片路径，当type=2时有值")
     * @SWG\Property(name="subject_item",type="Subject",description="专题 type=1时有值")
     * @SWG\Property(name="theme_item",description="主题type=0时有值",type="Themes")
     * @SWG\Property(name="goods",description="主题type=4时有值",type="BannerGoods")
     * @SWG\Property(name="order",type="integer",description="排序")
     */

    protected $appends=['subject_item','theme_item','goods'];

    public function getSubjectItemAttribute()
    {
        if($this->type==1){
            return Subject::find($this->item_id)->toArray();
        }else{
            return null;
        }
    }

    public function getThemeItemAttribute()
    {
        if($this->type==0){
            return Themes::find($this->item_id)->toArray();
        }else{
            return null;
        }
    }

    public function getGoodsAttribute()
    {
        //$goodsList=OrderGoods::with('goods')->where('order_id',$this->id)->get();
        $goodsList=BannerGoods::where('banner_id',$this->id)->get();
        if($goodsList == null) {
            return null;
        }
        return $goodsList->toArray();
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(id="Collection")
 */

class Collection extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="user_id",type="integer",description="用户id")
     * @SWG\Property(name="type",type="integer",description="类型，0---》商品 1---》主题")
     * @SWG\Property(name="item_id",type="integer",description="项id")
     * @SWG\Property(name="goods_item",type="Goods",description="收藏的商品type=0时有值")
     * @SWG\Property(name="theme_item",description="收藏的主题type=1时有值",type="Themes")
     */
    protected $appends=['goods_item','theme_item'];

    public function getGoodsItemAttribute()
    {
        if($this->type!=0){
            return null;
        }else{
            return Goods::find($this->item_id)->toArray();
        }
    }

    public function getThemeItemAttribute()
    {
        if($this->type!=0){
            return Themes::find($this->item_id)->toArray();
        }else{
            return null;
        }
    }
}

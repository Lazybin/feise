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
     * @SWG\Property(name="item",type="integer",description="收藏的项，type=0时为商品，1时为主题")
     */
    protected $appends=['item'];

    public function getItemAttribute()
    {
        if($this->type!=0){
            return Themes::find($this->item_id)->toArray();
        }else{
            return Goods::find($this->item_id)->toArray();
        }
    }
}

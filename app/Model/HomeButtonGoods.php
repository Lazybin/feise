<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(id="HomeButtonGoods")
 */
class HomeButtonGoods extends Model
{
    //
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="home_button_id",type="integer",description="home_button_id")
     * @SWG\Property(name="goods_id",type="integer",description="goods_id")
     * @SWG\Property(name="goods",type="Goods",description="商品详情")
     */
    protected $appends = ['goods'];

    public function getGoodsAttribute()
    {
        $goods=Goods::find($this->goods_id);
        if($goods == null) {
            return '';
        }
        return $goods->toArray();
    }
}

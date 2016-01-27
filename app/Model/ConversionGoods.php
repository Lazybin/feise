<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="ConversionGoods")
 */
class ConversionGoods extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="goods_id",type="integer",description="商品id")
     * @SWG\Property(name="goods",type="Goods",description="商品信息")
     */
    protected $fillable = [
        'goods_id'
    ];

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

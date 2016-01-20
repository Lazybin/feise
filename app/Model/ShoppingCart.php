<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="ShoppingCart")
 */
class ShoppingCart extends Model
{
    /**
     * @SWG\Property(name="user_id",type="integer",description="用户id")
     * @SWG\Property(name="goods_id",type="integer",description="商品id")
     * @SWG\Property(name="num",type="integer",description="购买数量")
     * @SWG\Property(name="goods",type="Goods",description="商品详情")
     */
    protected $fillable = [
        'user_id','goods_id','num'
    ];

    protected $appends=['goods'];

    public function getItemAttribute()
    {
        return Goods::find($this->goods_id)->toArray();
    }
}

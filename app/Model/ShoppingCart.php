<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(
 * id="newShoppingCartParams",
 * @SWG\Property(name="goods_id",type="integer",description="商品id"),
 * @SWG\Property(name="num",type="integer",description="购买数量"),
 * @SWG\Property(name="user_id",type="integer",description="用户id"),
 * @SWG\Property(name="properties",type="array",@SWG\Items("ordersGoodsProperties"),description="商品属性")
 * )
 */

/**
 * @SWG\Model(
 * id="editShoppingCartParams",
 * @SWG\Property(name="goods_list",type="array",@SWG\Items("editShoppingCartList"),description="商品列表")
 * )
 */

/**
 * @SWG\Model(
 * id="deleteShoppingCartParams",
 * @SWG\Property(name="goods_list",type="array",@SWG\Items("deleteShoppingCartList"),description="商品id列表")
 * )
 */

/**
 * @SWG\Model(
 * id="deleteShoppingCartList",
 * @SWG\Property(name="id",type="integer",description="购物车id")
 * )
 */

/**
 * @SWG\Model(
 * id="editShoppingCartList",
 * @SWG\Property(name="id",type="integer",description="购物车"),
 * @SWG\Property(name="num",type="integer",description="购买数量")
 * )
 */


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
        'user_id','goods_id','num','properties'
    ];

    protected $appends=['goods'];

    public function getGoodsAttribute()
    {
        return Goods::find($this->goods_id)->toArray();
    }
}

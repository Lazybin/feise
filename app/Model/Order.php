<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Model(
 * id="ordersGoodsList",
 * @SWG\Property(name="goods_id",type="integer",description="商品id"),
 * @SWG\Property(name="num",type="integer",description="购买数量"),
 * @SWG\Property(name="use_coupon",type="integer",description="是否使用抵用券0-->不启用,1-->启用"),
 * @SWG\Property(name="properties",type="array",@SWG\Items("ordersGoodsProperties"),description="商品属性"),
 * @SWG\Property(name="message",type="string",description="留言")
 * )
 */

/**
 * @SWG\Model(
 * id="ordersGoodsProperties",
 *  @SWG\Property(name="name",type="string",description="属性名"),
 *  @SWG\Property(name="value",type="string",description="属性值")
 * )
 */

/**
 * @SWG\Model(
 * id="newOrderParams",
 * @SWG\Property(name="user_id",type="integer",description="用户id"),
 * @SWG\Property(name="consignee",type="string",description="收货人"),
 * @SWG\Property(name="shipping_address",type="string",description="收货地址"),
 * @SWG\Property(name="mobile",type="string",description="收货人电话"),
 * @SWG\Property(name="goodsList",type="array",@SWG\Items("ordersGoodsList"),description="商品列表")
 * )
 */


/**
 * @SWG\Model(id="Order")
 */
class Order extends Model
{
    const PARTNER = '2088211506737974';
    const SELLER = '195793973@qq.com';

    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="user_id",type="integer",description="用户id")
     * @SWG\Property(name="out_trade_no",type="string",description="订单号")
     * @SWG\Property(name="consignee",type="string",description="收货人")
     * @SWG\Property(name="shipping_address",type="integer",description="收货地址")
     * @SWG\Property(name="mobile",type="string",description="收货人手机号")
     * @SWG\Property(name="total_fee",type="integer",description="要支付金额")
     * @SWG\Property(name="status",type="integer",description="状态：0--》未支付，1--》已支付，2--》取消，3-->已发货，4---》客户已签收，交易完成")
     * @SWG\Property(name="payment_time",type="string",description="支付时间")
     */
    protected $fillable = [
        'user_id', 'out_trade_no', 'consignee', 'shipping_address', 'mobile', 'total_fee', 'status', 'payment_time'
    ];
    //protected $appends = ['goods_list'];

    public function getGoodsListAttribute()
    {
        $goodsList=OrderGoods::where('order_id',$this->id)->get();
        if($goodsList == null) {
            return '';
        }
        return $goodsList->toArray();
    }
}

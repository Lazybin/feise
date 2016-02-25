<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(id="UseCouponRecords")
 */
class UseCouponRecords extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="user_id",type="integer",description="用户id")
     * @SWG\Property(name="coupon",type="integer",description="礼券使用金额")
     * @SWG\Property(name="order_id",type="integer",description="订单id")
     * @SWG\Property(name="order",type="Order",description="订单信息")
     */
    protected $appends = ['order'];

    public function getOrderAttribute()
    {
        //$goodsList=OrderGoods::with('goods')->where('order_id',$this->id)->get();
        $order=Order::find($this->order_id);
        if($order == null) {
            return '';
        }
        return $order->toArray();
    }
}

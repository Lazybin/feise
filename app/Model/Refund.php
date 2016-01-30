<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Model(id="Refund")
 */
class Refund extends Model
{
    /**
     * @SWG\Property(name="order_id",type="integer",description="订单id")
     * @SWG\Property(name="refund_reason",type="string",description="退款原因")
     * @SWG\Property(name="refund_explain",type="string",description="说明")
     * @SWG\Property(name="pic1",type="string",description="图片1")
     * @SWG\Property(name="pic2",type="string",description="图片2")
     * @SWG\Property(name="pic3",type="string",description="图片3")
     */
    protected $fillable = [
        'order_id','refund_reason','refund_explain','pic1','pic2','pic3','status'
    ];
}

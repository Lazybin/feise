<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="GiftTokenSetting")
 */

class GiftTokenSetting extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="name",type="integer",description="名称")
     * @SWG\Property(name="sum",type="integer",description="赠送金额")
     * @SWG\Property(name="status",type="integer",description="是否开启，0--》关闭 1---》开启")
     */
}

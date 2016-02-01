<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(
 * id="newShippingAddress",
 * @SWG\Property(name="user_id",type="integer",description="用户id"),
 * @SWG\Property(name="province",type="string",description="省名称"),
 * @SWG\Property(name="city",type="string",description="市名称"),
 * @SWG\Property(name="district",type="string",description="区\县名称"),
 * @SWG\Property(name="detailed_address",type="string",description="详细地址")
 * )
 */
/**
 * @SWG\Model(id="ShippingAddress")
 */
class ShippingAddress extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="user_id",type="integer",description="用户id")
     * @SWG\Property(name="province",type="string",description="省")
     * @SWG\Property(name="city",type="string",description="市")
     * @SWG\Property(name="district",type="string",description="区\县")
     * @SWG\Property(name="detailed_address",type="string",description="详细地址")
     */
    protected $fillable = [
        'user_id','province','city','district','detailed_address'
    ];

}

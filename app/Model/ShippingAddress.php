<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(
 * id="newShippingAddress",
 * @SWG\Property(name="user_id",type="integer",description="用户id"),
 * @SWG\Property(name="consignee",type="string",description="联系人"),
 * @SWG\Property(name="mobile",type="string",description="联系电话"),
 * @SWG\Property(name="is_default",type="integer",description="是否默认 0-->否，1--》是"),
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
     * @SWG\Property(name="consignee",type="string",description="联系人")
     * @SWG\Property(name="mobile",type="string",description="联系电话")
     * @SWG\Property(name="is_default",type="integer",description="是否默认 0-->否，1--》是")
     * @SWG\Property(name="province",type="string",description="省")
     * @SWG\Property(name="city",type="string",description="市")
     * @SWG\Property(name="district",type="string",description="区\县")
     * @SWG\Property(name="detailed_address",type="string",description="详细地址")
     */
    protected $fillable = [
        'user_id','consignee','mobile','is_default','province','city','district','detailed_address'
    ];

}

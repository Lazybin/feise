<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    //
    protected $fillable = [
        'name','price','original','price','use_coupon','coupon_amount','express_way','express_fee','returned_goods','description','detailed_introduction'
    ];
}

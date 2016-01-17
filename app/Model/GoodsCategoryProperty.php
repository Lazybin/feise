<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsCategoryProperty extends Model
{
    protected $fillable = [
        'category_property_id','goods_id','value'
    ];
}

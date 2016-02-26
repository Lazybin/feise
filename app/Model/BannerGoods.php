<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BannerGoods extends Model
{
    protected $appends = ['goods'];

    public function getGoodsAttribute()
    {
        $goods=Goods::find($this->goods_id);
        if($goods == null) {
            return '';
        }
        return $goods->toArray();
    }

}

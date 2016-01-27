<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="FreePostGoods")
 */
class FreePostGoods extends Model
{
    /**
     * @SWG\Property(name="free_posts_id",type="integer",description="分类id")
     * @SWG\Property(name="goods_id",type="integer",description="商品id")
     * @SWG\Property(name="free_posts",type="FreePost",description="分类详情")
     * @SWG\Property(name="goods",type="Goods",description="商品详情")
     */
    protected $fillable = [
        'free_posts_id','goods_id'
    ];
    protected $appends = ['free_posts','goods'];

    public function getGoodsAttribute()
    {
        $goods=Goods::find($this->goods_id);
        if($goods == null) {
            return '';
        }
        return $goods->toArray();
    }

    public function getFreePostsAttribute()
    {
        $activityClassification=FreePost::find($this->free_posts_id);
        if($activityClassification == null) {
            return '';
        }
        return $activityClassification->toArray();
    }
}

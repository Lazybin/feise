<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(id="ActivityClassificationGoods")
 */
class ActivityClassificationGoods extends Model
{
    /**
     * @SWG\Property(name="activity_classification_id",type="integer",description="分类id")
     * @SWG\Property(name="goods_id",type="integer",description="商品id")
     * @SWG\Property(name="activity_classifications",type="ActivityClassification",description="分类详情")
     * @SWG\Property(name="goods_id",type="Goods",description="商品详情")
     */
    protected $fillable = [
        'activity_classification_id','goods_id'
    ];

    protected $appends = ['activity_classifications','goods'];

    public function getGoodsAttribute()
    {
        $goods=Goods::find($this->goods_id);
        if($goods == null) {
            return '';
        }
        return $goods->toArray();
    }

    public function getActivityClassificationsAttribute()
    {
        $activityClassification=ActivityClassification::find($this->activity_classification_id);
        if($activityClassification == null) {
            return '';
        }
        return $activityClassification->toArray();
    }
}

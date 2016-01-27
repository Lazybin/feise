<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActivityClassificationGoods extends Model
{
    //
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

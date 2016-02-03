<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="Banner")
 */
class Banner extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="banner_position",type="string",description="图片位置 0--->首页 1--->优惠")
     * @SWG\Property(name="title",type="string",description="标题")
     * @SWG\Property(name="path",type="string",description="路径")
     * @SWG\Property(name="detail_image",type="string",description="详情图片路径")
     * @SWG\Property(name="order",type="integer",description="排序")
     */

    protected $appends=['subject_item','theme_item'];

    public function getSubjectItemAttribute()
    {
        if($this->type!=1){
            return null;
        }else{
            return Subject::find($this->item_id)->toArray();
        }
    }

    public function getThemeItemAttribute()
    {
        if($this->type!=0){
            return null;
        }else{
            return Themes::find($this->item_id)->toArray();
        }
    }
}

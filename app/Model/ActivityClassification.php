<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(id="ActivityClassification")
 */
class ActivityClassification extends Model
{
    /**
     * @SWG\Property(name="name",type="string",description="分类名称")
     * @SWG\Property(name="price",type="integer",description="排序")
     */
    protected $fillable = [
        'name','sort'
    ];
}

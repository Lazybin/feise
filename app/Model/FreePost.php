<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="FreePost")
 */
class FreePost extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="name",type="string",description="名字")
     * @SWG\Property(name="sort",type="integer",description="排序")
     * @SWG\Property(name="cover",type="string",description="封面图片")
     * @SWG\Property(name="head_image",type="string",description="页面顶部图片")
     */
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="Themes")
 */
class Themes extends Model
{
    /**
     * @SWG\Property(name="category",type="string",description="主题分类")
     * @SWG\Property(name="title",type="string",description="主题名称")
     * @SWG\Property(name="subhead",type="string",description="副标题")
     * @SWG\Property(name="category_id",type="integer",description="分类对应的id")
     * @SWG\Property(name="cover",type="string",description="封面图片")
     * @SWG\Property(name="head_image",type="string",description="页面顶部图片")
     * @SWG\Property(name="type",type="integer",description="0-->普通模式,1-->图文结合模式")
     * @SWG\Property(name="collect_count",type="integer",description="收藏数")
     * @SWG\Property(name="category",type="string",description="包含商品")
     * @SWG\Property(name="goods",type="Goods",description="包含商品")
     */
    protected $fillable = [
        'category_id','title','subhead','cover','head_image','description','type'
    ];

    protected $appends=['category','goods','collect_count'];

    public function getCategoryAttribute()
    {
        $category = Category::select('id','name','pid')->find($this->category_id);
        if($category == null) {
            return '';
        }
        return $category->toArray();
    }
    public function getGoodsAttribute()
    {
        $goods=ThemeGoods::join('goods','theme_goods.goods_id','=','goods.id')
            ->where('theme_id',$this->id)
            ->select('goods.*')->get()->toArray();

        return $goods;
    }

    public function getCollectCountAttribute()
    {
        return 0;
    }
}

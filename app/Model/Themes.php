<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Themes extends Model
{
    protected $fillable = [
        'category_id','title','category_id','cover','head_image','description','type'
    ];

    protected $appends=['category','goods'];

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
            ->select('goods.id','goods.name')->get()->toArray();

        return $goods;
    }
}

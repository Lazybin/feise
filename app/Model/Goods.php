<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    //
    protected $fillable = [
        'name','price','category_id','original','price','use_coupon','coupon_amount','express_way','express_fee','returned_goods','description','detailed_introduction'
    ];

    protected $appends=['category','properties'];

    public function category()
    {
        return $this->belongsTo('App\Model\Category');
    }

    public function getCategoryAttribute()
    {
        $category = Category::select('id','name','pid')->find($this->category_id);
        if($category == null) {
            return '';
        }
        return $category->toArray();
    }
    public function getPropertiesAttribute()
    {
        $categoryProperties=CategoryProperty::select('id','category_id','name','type')
            ->where('category_id',$this->category_id)->get()->toArray();


        foreach($categoryProperties as $k=>&$v){
            $v['values']=GoodsCategoryProperty::select('id','value')
                ->where('goods_id',$this->id)->where('category_property_id',$v['id'])->get()->toArray();
        }
        return $categoryProperties;
    }

}

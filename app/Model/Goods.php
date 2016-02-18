<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Model(id="Goods")
 */
class Goods extends Model
{
    /**
     * @SWG\Property(name="name",type="string",description="商品名称")
     * @SWG\Property(name="price",type="integer",description="现价")
     * @SWG\Property(name="detailed_introduction",type="string",description="原价")
     * @SWG\Property(name="num",type="integer",description="库存")
     * @SWG\Property(name="category_id",type="integer",description="分类对应的id")
     * @SWG\Property(name="evaluation_person_image",type="string",description="评测师图片")
     * @SWG\Property(name="evaluation_content",type="string",description="评测内容")
     * @SWG\Property(name="cover",type="string",description="封面图片")
     * @SWG\Property(name="use_coupon",type="integer",description="是否启用礼券额外抵用0-->不启用,1-->启用")
     * @SWG\Property(name="coupon_amount",type="integer",description="礼券抵用的金额")
     * @SWG\Property(name="express_way",type="integer",description="快递方式 0:免邮，1:普通快递，2:EMS快递，3:新疆、青海、西藏等地区费用")
     * @SWG\Property(name="express_fee",type="integer",description="快递费用")
     * @SWG\Property(name="returned_goods",type="integer",description="是否支持七天无理由退货 0：不支持，1：支持")
     * @SWG\Property(name="goods_description",type="string",description="商品描述")
     * @SWG\Property(name="collect_count",type="integer",description="收藏数")
     * @SWG\Property(name="comments_count",type="integer",description="评论数")
     * @SWG\Property(name="share_times",type="integer",description="分享次数")
     * @SWG\Property(name="has_collection",type="integer",description="是否收藏，0-》未收藏，1-》已收藏")
     * @SWG\Property(name="detailed_introduction",type="string",description="详细描述（富文本框）")
     * @SWG\Property(name="category",type="string",description="所属分类 id-->分类id，name-->分类名称")
     * @SWG\Property(name="properties",type="array",description="所属分类 id-->分类id，name-->属性名字，type-->属性类型 0->选项 1->数字，properties-->属性对应的值列表(id->值id,value-->名称)")
     * @SWG\Property(name="images",type="array",description="展示图片列表 image_id-->图片id，path-->图片路径")
     * @SWG\Property(name="comments",type="array",description="评论列表")
     */
    protected $fillable = [
        'name','price','category_id','evaluation_person_image','evaluation_content','cover','original_price','use_coupon','coupon_amount','express_way','express_fee','returned_goods','goods_description','detailed_introduction','num','share_times'
    ];

    protected $appends=['category','properties','images','collect_count','comments_count'];

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

    public function getImagesAttribute()
    {
        $images=GoodsImages::join('images','images.id','=','goods_images.image_id')
            ->where('goods_id',$this->id)
            ->select('goods_images.image_id','images.path')
            ->get()->toArray();

        return $images;
    }

    public function getCommentsCountAttribute()
    {
        return UserComment::where('type',0)->where('item_id',$this->id)->count();
    }

    public function getCollectCountAttribute()
    {
        return Collection::where('type',0)->where('item_id',$this->id)->count();
    }

}

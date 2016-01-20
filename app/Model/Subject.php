<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="Subject")
 */
class Subject extends Model
{
    /**
     * @SWG\Property(name="title",type="string",description="标题")
     * @SWG\Property(name="subhead",type="string",description="副标题")
     * @SWG\Property(name="cover",type="string",description="封面")
     * @SWG\Property(name="themes",type="Themes",description="主题列表")
     * @SWG\Property(name="collect_count",type="integer",description="收藏数")
     * @SWG\Property(name="is_new",type="integer",description="是否是今天发布的 0---》否 1----》是")
     */
    protected $fillable = [
        'title','subhead','cover'
    ];

    protected $appends=['themes','collect_count','is_new'];

    public function getThemesAttribute()
    {
        $goods=SubjectThemes::join('themes','subject_themes.theme_id','=','themes.id')
            ->where('subject_id',$this->id)
            ->select('themes.*')->get()->toArray();

        return $goods;
    }
    public function getIsNewAttribute()
    {
        $t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        $create_time= strtotime($this->created_at);
        if($create_time>=$start&&$create_time<=$end)
            return 1;
        else
            return 0;

    }

    public function getCollectCountAttribute()
    {
        return 0;
    }
}

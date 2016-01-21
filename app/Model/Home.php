<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Model(id="Home")
 */

class Home extends Model
{
    /**
     * @SWG\Property(name="item_id",type="integer",description="项对应的ID")
     * @SWG\Property(name="type",type="integer",description="类型 0--->专题 1--->主题")
     * @SWG\Property(name="sort",type="integer",description="排序")
     * @SWG\Property(name="item",type="array",description="包含项 当type=0 其内容为专题，type=1其内容为主题")
     */
    protected $fillable = [
        'item_id','type','sort'
    ];
    protected $appends=['item'];

    public function getItemAttribute()
    {
        if($this->type==0){
            $subjects=Subject::find($this->item_id)->toArray();
            $themes=$subjects['themes'];
            $subjects['themes']=[];
            foreach($themes as $t){
                array_push($subjects['themes'],$t['themes'][0]);
            }
            return $subjects;
        }else{
            return Themes::find($this->item_id)->toArray();
        }
    }
}

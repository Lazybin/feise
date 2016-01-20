<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //
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

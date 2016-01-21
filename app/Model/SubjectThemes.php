<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubjectThemes extends Model
{
    //
    protected $appends=['themes'];

    public function getThemesAttribute()
    {
        $themes=Themes::where('id',$this->theme_id)->select()
            ->get()->toArray();

        return $themes;
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $fillable = [
        'item_id','type','sort'
    ];
    protected $appends=['item'];

    public function getItemAttribute()
    {
        if($this->type==0){
            return Subject::find($this->item_id)->toArray();
        }else{
            return Themes::find($this->item_id)->toArray();
        }
    }
}

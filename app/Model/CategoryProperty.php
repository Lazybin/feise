<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryProperty extends Model
{
    //
    protected $fillable = [
        'name','type','category_id'
    ];
}

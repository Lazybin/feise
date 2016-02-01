<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Model(id="Area")
 */
class Area extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="name",type="string",description="名称")
     * @SWG\Property(name="pid",type="integer",description="父id")
     */
    protected $table = 'area';
}

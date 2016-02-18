<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(
 * id="HotSearchRecords",
 * @SWG\Property(name="search_times",type="integer",description="搜索次数"),
 * @SWG\Property(name="keywords",type="string",description="关键字")
 * )
 */

/**
 * @SWG\Model(
 * id="SelfLast",
 * @SWG\Property(name="keywords",type="string",description="关键字")
 * )
 */

/**
 * @SWG\Model(id="SearchRecords")
 */
class SearchRecords extends Model
{
    /**
     * @SWG\Property(name="hot",type="HotSearchRecords",description="大家搜索")
     * @SWG\Property(name="self_last",type="SelfLast",description="最近搜索")
     */
}

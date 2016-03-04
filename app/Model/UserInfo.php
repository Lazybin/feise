<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Model(id="UserInfo")
 */
class UserInfo extends Model
{
    /**
     * @SWG\Property(name="id",type="integer",description="id")
     * @SWG\Property(name="user_account",type="string",description="用户账号")
     * @SWG\Property(name="nick_name",type="string",description="用户昵称")
     * @SWG\Property(name="head_icon",type="string",description="用户头像地址")
     */
}

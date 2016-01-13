<?php
/**
 * Created by PhpStorm.
 * User: ab
 * Date: 2016/1/13
 * Time: 20:13
 */

namespace App\Model;


use Illuminate\Contracts\Support\Jsonable;


class BaseResponse implements Jsonable
{
    const CODE_OK = 0;
    const CODE_ERROR_AUTH = 1;
    const CODE_ERROR_CHECK = 2;
    const CODE_ERROR_BUSINESS = 3;
    const CODE_ERROR_PROGRAM = 4;

    public $Code = 0;
    public $Message;
    public $Description;
    public $StackTrace;
    public $Data;
    public $total = -1;
    public $rows = [];

    function __construct($code = 0, $message = null, $description = null) {
        $this->Code = $code;
        $this->Message = $message;
        $this->Description = $description;
    }

    public function toJson($options = 0) {
        return json_encode($this, $options);
    }
}
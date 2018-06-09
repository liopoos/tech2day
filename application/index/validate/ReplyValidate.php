<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 08/05/2018
 * Time: 11:25
 */

namespace app\index\validate;


use think\Validate;

class ReplyValidate extends Validate
{
    protected $rule = [
        'content|回复内容' => 'require|max:2000',
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 06/05/2018
 * Time: 20:30
 */

namespace app\index\validate;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'username|用户名' => 'require|max:20|alphaNum',
        'password|密码' => 'require|max:25|min:8',
    ];
}
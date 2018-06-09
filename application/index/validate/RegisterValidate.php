<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 06/05/2018
 * Time: 21:25
 */

namespace app\index\validate;


use think\Validate;

class RegisterValidate extends Validate
{
    protected $rule = [
        'username|用户名' => 'require|max:20|alphaNum',
        'password|密码' => 'require|max:25|min:8',
        'email|电子邮箱' => 'require|email',
    ];
}
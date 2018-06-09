<?php
# @Author: hades
# @Date:   2018-05-08T12:05:39+08:00
# @Email:  i@mayuko.cn
# @Last modified by:   hades
# @Last modified time: 2018-05-24T14:56:23+08:00


/**
 * Created by PhpStorm.
 * User: hades
 * Date: 07/05/2018
 * Time: 23:29
 */

namespace app\index\validate;


use think\Validate;

class TopicValidate extends Validate
{
    protected $rule = [
        'title|标题' => 'require|max:100',
        'content|正文' => 'require|max:20000',
    ];
}

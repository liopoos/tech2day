<?php
/**
 * Created by PhpStorm.
 * UserModel: hades
 * Date: 05/05/2018
 * Time: 23:26
 */

namespace app\index\model;

use think\Model;

class UserModel extends Model
{
    protected $table = 'user_list';

    public function getHeaderAttr($value, $data)
    {
        return md5($data['email']);
    }

    public function getJoinTimeAttr($value)
    {
        return Date('Y年m月d日 H:i', $value);
    }
}
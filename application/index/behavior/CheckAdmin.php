<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/5/16
 * Time: 17:31
 */

namespace app\index\behavior;


use app\index\model\UserModel;
use think\Session;

class CheckAdmin
{
    public function run()
    {
        if (Session::has('admin')) {
            $token = UserModel::get(['username' => Session::get('admin')]);
            if ($token['level'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
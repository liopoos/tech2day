<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 09/05/2018
 * Time: 14:21
 */

namespace app\index\behavior;


use app\index\model\UserModel;
use think\Session;

class CheckAuth
{
    public function run()
    {
        if (Session::has('username')) {
            $token = UserModel::get(['username' => Session::get('username')]);
            if ($token['loginToken'] == Session::get('token')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 09/05/2018
 * Time: 20:16
 */

namespace app\index\controller;


use app\index\model\UserModel;
use think\Controller;
use think\Cookie;
use think\Hook;
use think\Session;

class BaseController extends Controller
{
    public function _initialize()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAuth', 'run')) {
            $user = UserModel::get(['username' => Session::get('username')]);
            Cookie::forever('theme', $user['theme']);
            Cookie::forever('css', $user['customStyle']);
        } else {
            Cookie::forever('theme', 1);
        }
    }

}
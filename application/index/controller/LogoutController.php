<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 06/05/2018
 * Time: 21:16
 */

namespace app\index\controller;


use think\Controller;
use think\Cookie;
use think\Hook;
use think\Session;

class LogoutController extends BaseController
{
    public function index()
    {
        Session::clear();
        Cookie::clear();
        $this->assign('title', 'Tech2Day | 注销');
        return $this->fetch('page/logout');

    }
}
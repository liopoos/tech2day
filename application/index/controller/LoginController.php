<?php

namespace app\index\controller;

use app\index\model\UserModel;
use think\Controller;
use think\Cookie;
use think\Hook;
use think\Loader;
use think\Request;
use think\Session;

/**
 * Created by PhpStorm.
 * User: hades
 * Date: 06/05/2018
 * Time: 20:22
 */
class LoginController extends BaseController
{
    public function index()
    {
        if (!Hook::exec('app\\index\\behavior\\CheckAuth', 'run')) {
            if (Request::instance()->isPost()) {
                $post = Request::instance()->post();
                $validate = Loader::validate('LoginValidate');
                if (!$validate->check($post)) {
                    $this->assign('error', $validate->getError());
                } else {
                    if (!$this->login($post)) {
                        $this->assign('error', '密码错误，请重新输入');
                    } else {
                        $this->redirect('/', 302);
                    }
                }
            }
            $this->assign('title', 'Tech2Day | 登录');
            return $this->fetch('page/login');
        } else {
            $this->redirect('/', 302);
        }

    }

    private function login($data)
    {
        $user = UserModel::get(['username' => $data['username']]);
        if ($user['password'] == md5($data['password'])) {
            $updateToken = UserModel::get(['username' => $data['username']]);
            $updateToken->loginToken = $data['token'];
            $updateToken->save();
            Session::set('userid', $user['userId']);
            Session::set('email', $user['email']);
            Session::set('token', $data['token']);
            Session::set('username', $user['username']);//保存会话
            Cookie::set('token', $data['token']);
            return true;
        } else {
            return false;
        }
    }

}
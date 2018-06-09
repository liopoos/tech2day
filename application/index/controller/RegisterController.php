<?php
# @Author: hades
# @Date:   2018-05-17T17:17:05+08:00
# @Email:  i@mayuko.cn
# @Last modified by:   hades
# @Last modified time: 2018-06-01T08:29:12+08:00


/**
 * Created by PhpStorm.
 * User: hades
 * Date: 06/05/2018
 * Time: 21:33
 */

namespace app\index\controller;


use app\index\model\UserModel;
use think\Controller;
use think\Cookie;
use think\Hook;
use think\Loader;
use think\Request;
use think\Session;

class RegisterController extends BaseController
{
    public function index()
    {
        if (!Hook::exec('app\\index\\behavior\\CheckAuth', 'run')) {
            if (Request::instance()->isPost()) {
                $post = Request::instance()->post();
                $validate = Loader::validate('RegisterValidate');
                if (!$validate->check($post)) {
                    $this->assign('error', $validate->getError());
                } else {
                    if($post['password-confirm'] == $post['password']){
                        if (!$this->register($post)) {
                            $this->assign('error', '用户名已存在');
                        } else {
                                $user = UserModel::get(['username' => $post['username']]);
                                Session::set('userid', $user['userId']);
                                Session::set('email', $user['email']);
                                Session::set('token', $user['loginToken']);
                                Session::set('username', $user['username']);//保存会话
                                $this->redirect('/', 302);//重定向到首页
                        }
                    }else{
                        $this->assign('error', '两次密码输入不一致');
                    }

                }
            }
            $this->assign('title', 'Tech2Day | 注册');
            return $this->fetch('page/register');
        } else {
            $this->redirect('/', 302);//重定向到首页
        }

    }

    private function register($data)
    {
        $hasUser = UserModel::where('username', $data['username'])->count();//判断用户名是否存在
        if ($hasUser) {
            return false;
        } else {
            $user = new UserModel();
            $user->data([
                'username' => $data['username'],
                'password' => md5($data['password']),
                'email' => $data['email'],
                'joinTime' => time(),
                'profile' => 'Hello world.',
                'loginToken' => $data['token'],
            ]);
            $user->save();
            return true;
        }
    }

}

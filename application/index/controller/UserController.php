<?php
# @Author: hades
# @Date:   2018-05-09T21:04:19+08:00
# @Email:  i@mayuko.cn
# @Last modified by:   hades
# @Last modified time: 2018-05-24T15:03:04+08:00


/**
 * Created by PhpStorm.
 * User: hades
 * Date: 08/05/2018
 * Time: 12:16
 */

namespace app\index\controller;


use app\index\model\ReplyModel;
use app\index\model\TopicModel;
use app\index\model\UserModel;
use think\Controller;
use think\Hook;
use think\Request;
use think\Session;

class UserController extends BaseController
{
    public function index($name)
    {

        $userInfo = UserModel::get(['username' => $name]);//获取用户信息
        $userTopic = TopicModel::all((function ($query) use ($userInfo) {
            $query->where('userId', $userInfo['userId'])->order('creatTime', 'DESC')->limit(5);
        }));//获取创建的话题
        $userReply = ReplyModel::all((function ($query) use ($userInfo) {
            $query->where('userId', $userInfo['userId'])->order('replyTime', 'DESC')->limit(5);
        }));//获取回复的话题
        $this->assign('userInfo', $userInfo);
        $this->assign('userTopic', $userTopic);
        $this->assign('userReply', $userReply);
        $this->assign('title', $name . ' | Tech2Day');
        return $this->fetch('page/user');
    }

    public function creat($name)
    {
        $userInfo = UserModel::get(['username' => $name]);
        $userTopic = TopicModel::where('userId', $userInfo['userId'])->order('creatTime', 'DESC')->paginate(10);
        $this->assign('userTopic', $userTopic);
        $this->assign('title', $name . '创建的话题 | Tech2Day');
        return $this->fetch('page/creat');
    }

    public function reply($name)
    {
        $userInfo = UserModel::get(['username' => $name]);
        $userReply = ReplyModel::where('userId', $userInfo['userId'])->order('replyTime', 'DESC')->paginate(10);
        $this->assign('userReply', $userReply);
        $this->assign('title', $name . '回复的话题 | Tech2Day');
        return $this->fetch('page/reply');
    }

    public function notifications()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAuth', 'run')) {
            $username = Session::get('username');
            $atList = ReplyModel::all();
            $notice = array();
            foreach ($atList as $at) {
                $atUser = explode(',', $at['atUser']);
                if (in_array($username, $atUser)) {
                    array_push($notice, $at);
                }
            }
            $this->assign('noticeList', $notice);
            $this->assign('title', '提醒 | Tech2Day');
            return $this->fetch('page/notifications');
        } else {
            $this->error('未登录');
        }
    }

    public function setting()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAuth', 'run')) {
            if (Request::instance()->isPost()) {
                $post = Request::instance()->post();
                if ($post['method'] == 'user') {
                    if ($this->changeUserInfo($post)) {
                        $this->assign('success', '个人设置修改成功');
                    } else {
                        $this->assign('error', '发生错误，无法完成更改');
                    }
                } elseif ($post['method'] == 'password') {
                    $result = $this->validate(
                        $post,
                        [
                            'current-password' => 'require',
                            'password' => 'require|max:25|confirm',
                            'password_confirm' => 'require|max:25',
                        ]);
                    if (true !== $result) {
                        $this->assign('error', $result);
                    } else {
                        if ($this->changePassword($post)) {
                            $this->assign('success', '密码成功被修改');
                        } else {
                            $this->assign('error', '密码验证错误');
                        }
                    }
                }
            }
            $userInfo = UserModel::get(['username' => Session::get('username')]);
            $this->assign('title', '个人设置 | Tech2Day');
            $this->assign('userInfo', $userInfo);
            return $this->fetch('page/setting');
        } else {
            //$this->redirect('/', 302);
            $this->error('未登录');
        }
    }

    private function changeUserInfo($data)
    {
        $change = UserModel::get(['username' => Session::get('username')]);
        $change->profile = $data['profile'];
        $change->customStyle = $data['customcss'];
        $change->theme = $data['theme'];
        $change->save();
        return true;
    }

    private function changePassword($data)
    {
        $change = UserModel::get(['username' => Session::get('username')]);
        if ($change['password'] == md5($data['current-password'])) {
            $change->password = md5($data['password']);
            $change->save();
            return true;
        } else {
            return false;
        }
    }

}

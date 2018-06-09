<?php
# @Author: hades
# @Date:   2018-05-17T17:17:42+08:00
# @Email:  i@mayuko.cn
# @Last modified by:   hades
# @Last modified time: 2018-05-24T15:09:03+08:00


/**
 * Created by PhpStorm.
 * User: hades
 * Date: 07/05/2018
 * Time: 19:09
 */

namespace app\index\widget;


use app\index\model\ReplyModel;
use app\index\model\TopicModel;
use app\index\model\UserModel;
use think\Db;
use think\Session;

class SideBar
{
    public function userInfo()
    {
        if (Session::has('username')) {
            $user = UserModel::get(['username' => Session::get('username')]);
            return $user;
        } else {
            return null;
        }

    }

    public function hotTopic()
    {
        $hotList = Db::table('topic_list topic,user_list user')
            ->where('topic.topicId', 'IN', function ($query) {
                $query->table('reply_list')->field('topicId')
                    ->group('topicId')->orderRaw('COUNT(topicId) DESC');
            })->where('topic.userId = user.userId')->field('topic.*,user.email')->order('creatTime', 'DESC')->limit(5)->select();
        return $hotList;
    }

    public function forumInfo()
    {
        $forumInfo = array();
        $forumInfo['topicCount'] = TopicModel::count();
        $forumInfo['replyCount'] = ReplyModel::count();
        $forumInfo['userCount'] = UserModel::count();
        return $forumInfo;
    }

    public function starTopic()
    {
        $starList = TopicModel::all(function ($query) {
            $query->where('star', 1)->order('creatTime', 'DESC')->limit(5);
        });
        return $starList;
    }

    public function systemInfo()
    {
        $systemInfo = array();
        $systemInfo['phpVersion'] = PHP_VERSION;
        $systemInfo['system'] = PHP_OS;
        $systemInfo['now'] = time();
        return $systemInfo;
    }

}

<?php
# @Author: hades
# @Date:   2018-05-10T13:41:48+08:00
# @Email:  i@mayuko.cn
# @Last modified by:   hades
# @Last modified time: 2018-05-24T15:13:32+08:00


/**
 * Created by PhpStorm.
 * UserModel: hades
 * Date: 05/05/2018
 * Time: 23:36
 */

namespace app\index\controller;

use app\index\model\ReplyModel;
use app\index\model\TopicModel;
use app\index\model\UserModel;
use think\Loader;
use think\Request;
use think\Session;

class TopicController extends BaseController
{
    public function index($id)
    {
        $this->addCount($id);//增加点击量

        $replyList = (ReplyModel::where('topicId', $id)->count() > 0) ? ReplyModel::all(['topicId' => $id]) : null;
        for ($i = 0; $i < count($replyList); $i++) {
            $replyList[$i]['userInfo'] = UserModel::get(['userId' => $replyList[$i]['userId']]);
        }
        $topicContent = TopicModel::get(['topicId' => $id]);
        $userInfo = UserModel::get(['userId' => $topicContent['userId']]);

        $this->assign('topicContent', $topicContent);
        $this->assign('userInfo', $userInfo);
        $this->assign('replyList', $replyList);
        $this->assign('title', $topicContent['title'] . ' | Tech2Day');
        $this->assign('countReplyItem', count($replyList));

        echo Request::instance()->header('cookies');


        if (Request::instance()->isPost()) {
            $validate = Loader::validate('ReplyValidate');
            if ($validate->check(Request::instance()->post())) {
                if ($this->reply($id, Request::instance()->post())) {
                    $post = Request::instance()->post();
                    $replyId = ReplyModel::get(['token' => $post['token']]);
                    $this->redirect('/t/' . $id . '#reply' . $replyId['replyId'], 302);
                }
            } else {
                $this->assign('error', $validate->getError());
            }
        }
        return $this->fetch('page/topic');
    }

    private function addCount($id)
    {
        $topic = TopicModel::get(['topicId' => $id]);
        $topic->visitNum += 1;
        $topic->save();
    }

    private function reply($id, $post)
    {

        $check = (ReplyModel::where('token', $post['token'])->count() > 0) ? true : false;
        if (!$check) {
            $atNum = preg_match_all('/(?<=@)\w+/si', $post['content'], $atArray);
            if ($atNum > 0) {
                $at = implode(",", $atArray[0]);
            } else {
                $at = NULL;
            }
            $reply = new ReplyModel();
            $reply->data([
                'topicId' => $id,
                'content' => $post['content'],
                'userId' => Session::get('userid'),
                'replyTime' => time(),
                'userUA' => Request::instance()->header('user-agent'),
                'isRead' => 0,
                'atUser' => $at,
                'token' => $post['token'],
            ]);
            $reply->save();
            $last = TopicModel::get(['topicId' => $id]);
            $last->lastReply = Session::get('username');
            $last->save();
            return true;
        } else {
            return false;
        }

    }

    public function newTopic()
    {
        if (Session::has('username')) {
            if (Request::instance()->isPost()) {
                $post = Request::instance()->post();
                $validate = Loader::validate('TopicValidate');
                if (!$validate->check($post)) {
                    $this->assign('error', $validate->getError());
                } else {
                    if ($this->insertNewTopic($post)) {
                        $topicId = TopicModel::getByToken($post['token']);
                        $this->redirect('/t/' . $topicId['topicId'], 302);
                    } else {
                        $this->assign('error', '请勿重复提交');
                    }
                }
            }
            $this->assign('title', 'Tech2Day | 新的话题');
            return $this->fetch('page/new');
        } else {
            $this->redirect('/', 302);
        }
    }

    private function insertNewTopic($data)
    {
        $check = (ReplyModel::where('token', $data['token'])->count() > 0) ? true : false;
        if (!$check) {
            $topic = new TopicModel();
            $topic->data([
                'node' => $data['node'],
                'title' => $data['title'],
                'content' => $data['content'],
                'lastReply' => Session::get('username'),
                'creatTime' => time(),
                'userId' => Session::get('userid'),
                'token' => $data['token'],
            ]);
            $topic->save();
            return true;
        } else {
            return false;
        }
    }


}

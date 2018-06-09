<?php
/**
 * Created by PhpStorm.
 * UserModel: hades
 * Date: 06/05/2018
 * Time: 14:38
 */

namespace app\index\model;


use CI_User_agent;
use HyperDown\Parser;
use think\Loader;
use think\Model;

class ReplyModel extends Model
{
    protected $table = 'reply_list';


    public function getReplyTimeAttr($value)
    {
        return Date('Y年m月d日 H:i', $value);
    }

    public function getUserUaAttr($value)
    {
        Loader::import('./user_agent');
        $ua = new CI_User_agent($value);
        return $ua->platform();
    }

    public function getTopicContentAttr($value, $data)
    {
        $topic = TopicModel::get(['topicId' => $data['topicId']]);
        return $topic;
    }

    public function getUserInfoAttr($value, $data)
    {
        $userInfo = UserModel::get(['userId' => $data['userId']]);
        return $userInfo;
    }

    public function getContentAttr($value)
    {
        Loader::import('./Parser');
        $parser = new Parser();
        return $parser->makeHtml($value);
    }
}
<?php
/**
 * Created by PhpStorm.
 * UserModel: hades
 * Date: 05/05/2018
 * Time: 23:30
 */

namespace app\index\model;

use HyperDown\Parser;
use think\Loader;
use think\Model;

class TopicModel extends Model
{
    protected $table = 'topic_list';

    public function getCreatTimeAttr($value)
    {
        return Date('Y年m月d日 H:i', $value);
    }

    public function getNodeAttr($value)
    {
        $nodeName = NodeModel::get(['nodeId' => $value]);
        return $nodeName['name'];
    }

    public function getUserInfoAttr($value, $data)
    {
        $userInfo = UserModel::get(['userId' => $data['userId']]);
        return $userInfo;
    }

    public function getReplyNumAttr($value, $data)
    {
        $replyNum = ReplyModel::where('topicId', $data['topicId'])->count();
        return $replyNum;
    }

    public function getContentAttr($value)
    {
        Loader::import('./Parser');
        $parser = new Parser();
        return $parser->makeHtml($value);
    }


}
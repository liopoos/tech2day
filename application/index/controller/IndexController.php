<?php

namespace app\index\controller;

use app\index\model\TopicModel;
use think\Request;

class IndexController extends BaseController
{

    public function index()
    {
        $topicAllList = TopicModel::order('creatTime', 'DESC')
            ->paginate(20);
        $this->assign('nodeId', 10000);
        $this->assign('topicList', $topicAllList);//传递话题列表
        $this->assign('title', 'Tech2Day');//传递话题列表
        return $this->fetch('page/index');
    }

    public function node($id)
    {
        $topicNodeList = TopicModel::where('node', $id)
            ->order('creatTime', 'DESC')
            ->paginate(20);
        $this->assign('nodeId', $id);
        $this->assign('topicNodeList', $topicNodeList);
        $this->assign('title', '节点 | Tech2Day');
        return $this->fetch('page/node');
    }

    public function search()
    {
        $text = '';
        $data = Request::instance()->get();
        if (array_key_exists('text', $data)) {
            $text = $data['text'];
        } else {
            $this->error('请输入搜索内容');
        }
        $searchList = TopicModel::where('content', 'like', '%' . $text . '%')->paginate(20);;
        $this->assign('searchList', $searchList);
        $this->assign('text', $text);
        $this->assign('title', '搜索关于' . $text . '的内容 | Tech2Day');
        return $this->fetch('page/search');

    }

}

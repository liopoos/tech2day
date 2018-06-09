<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/5/16
 * Time: 21:31
 */

namespace app\index\controller;


use app\index\model\TopicModel;
use think\Db;

class ApiController extends BaseController
{
    public function hot()
    {
        $hotList = Db::table('topic_list topic,user_list user')
            ->where('topic.topicId', 'IN', function ($query) {
                $query->table('reply_list')->field('topicId')
                    ->group('topicId')->orderRaw('COUNT(topicId) DESC');
            })->where('topic.userId = user.userId')->field('topic.*,user.email')->limit(5)->select();
        echo json_encode($hotList);
    }

    public function topic()
    {
        $id = input('get.id');
        $topicList = TopicModel::get($id);
        echo $topicList->toJson();
    }

}
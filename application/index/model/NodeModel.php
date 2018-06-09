<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 07/05/2018
 * Time: 23:48
 */

namespace app\index\model;


use think\Model;

class NodeModel extends Model
{
    protected $table = 'node_list';

    public function getCountAttr($value, $data)
    {
        return TopicModel::where('node', $data['nodeId'])->count();
    }
}
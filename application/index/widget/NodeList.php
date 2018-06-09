<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 07/05/2018
 * Time: 23:49
 */

namespace app\index\widget;


use app\index\model\NodeModel;

class NodeList
{
    public function nodeList()
    {
        return NodeModel::all();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 08/05/2018
 * Time: 11:50
 */

namespace app\index\model;


use HyperDown\Parser;
use think\Loader;
use think\Model;

class PageModel extends Model
{
    protected $table = 'page_list';

    public function getCreatTimeAttr($value)
    {
        return Date('Y年m月d日 H:i', $value);
    }

    public function getContentAttr($value)
    {
        Loader::import('./Parser');
        $parser = new Parser();
        return $parser->makeHtml($value);
    }

    public function getNoStyleAttr($value, $data)
    {
        return $data['content'];
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 08/05/2018
 * Time: 11:45
 */

namespace app\index\controller;


use app\index\model\PageModel;
use think\Controller;

class PageController extends BaseController
{
    public function index($id)
    {
        $pageContent = PageModel::get(['pageId' => $id]);
        $this->assign('title', $pageContent['title']." | Tech2Day");
        $this->assign('pageContent', $pageContent);
        return $this->fetch('page/page');
    }

}
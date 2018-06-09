<?php
/**
 * Created by PhpStorm.
 * User: hades
 * Date: 09/05/2018
 * Time: 20:55
 */

namespace app\index\widget;


use think\Cookie;

class Custom
{
    public function theme()
    {
        switch (Cookie::get('theme')) {
            case 1:
                $theme = 'bootstrap.main.css';
                break;
            case 2:
                $theme = 'bootstrap.inverse.css';
                break;
            case 3:
                $theme = 'bootstrap.yeti.css';
                break;
            case 4:
                $theme = 'bootstrap.editor.css';
                break;
            default:
                $theme = 'bootstrap.main.css';
        }
        return $theme;
    }
}
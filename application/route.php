<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//路由变量规则
Route::pattern([
    'name' => '\w+',
    'id' => '\d+',
]);

Route::get('/', 'index.php/index/index/index');
Route::any('t/:id', 'index.php/index/topic/index');
Route::get('p/:id', 'index.php/index/page/index');
Route::get('n/:id', 'index.php/index/index/node');
Route::get('u/:name', 'index.php/index/user/index');
Route::get('c/:name', 'index.php/index/user/creat');
Route::get('r/:name', 'index.php/index/user/reply');
Route::get('s/', 'index.php/index/index/search');

Route::get('notifications', 'index.php/index/User/notifications');
Route::any('login', 'index.php/index/login/index');
Route::any('logout', 'index.php/index/logout/index');
Route::any('register', 'index.php/index/register/index');
Route::any('new', 'index.php/index/topic/newtopic');
Route::any('setting', 'index.php/index/user/setting');

Route::any('admin$', 'index.php/index/admin/login');
Route::get('admin/t$', 'index.php/index/admin/topic');
Route::get('admin/r/[:id]', 'index.php/index/admin/reply');
Route::get('admin/n$', 'index.php/index/admin/node');
Route::get('admin/p$', 'index.php/index/admin/page');
Route::get('admin/u$', 'index.php/index/admin/user');
Route::get('admin/logout$', 'index.php/index/admin/logout');

Route::get('admin/t/star/', 'index.php/index/admin/starTopic');
Route::get('admin/t/cancel/', 'index.php/index/admin/cancelTopic');
Route::get('admin/t/delete/', 'index.php/index/admin/deleteTopic');
Route::get('admin/r/delete/', 'index.php/index/admin/deleteReply');
Route::get('admin/n/delete/', 'index.php/index/admin/deleteNode');
Route::get('admin/n/add/', 'index.php/index/admin/addNode');
Route::get('admin/u/add', 'index.php/index/admin/addAdmin');
Route::get('admin/u/delete/', 'index.php/index/admin/deleteAdmin');
Route::post('admin/p/add/', 'index.php/index/admin/addPage');
Route::get('admin/p/delete/', 'index.php/index/admin/deletePage');
Route::any('admin/p/change/:id', 'index.php/index/admin/changePage');

Route::get('api/hot', 'index.php/index/api/hot');
Route::get('api/topic', 'index.php/index/api/topic');

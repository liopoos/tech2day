<?php
# @Author: hades
# @Date:   2018-05-16T21:58:32+08:00
# @Email:  i@mayuko.cn
# @Last modified by:   hades
# @Last modified time: 2018-05-31T23:54:42+08:00


/**
 * Created by PhpStorm.
 * User: hades
 * Date: 2018/5/16
 * Time: 15:09
 */

namespace app\index\controller;

use app\index\model\NodeModel;
use app\index\model\PageModel;
use app\index\model\ReplyModel;
use app\index\model\TopicModel;
use app\index\model\UserModel;
use think\Cookie;
use think\Hook;
use think\Loader;
use think\Request;
use think\Session;

class AdminController extends BaseController
{
    public function topic()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $topicList = TopicModel::all();
            $this->assign('topicList', $topicList);
            return $this->fetch('admin/topic');
        } else {
            $this->error('管理员未登录');
        }
    }

    public function logout()
    {
        Session::delete('admin');
        $this->redirect('/', 302);
    }

    public function login()
    {
        if (!Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            if (Request::instance()->isPost()) {
                $data = Request::instance()->post();
                $validate = Loader::validate('LoginValidate');
                if (!$validate->check($data)) {
                    $this->assign('error', $validate->getError());
                } else {
                    $user = UserModel::get(['username' => $data['username']]);
                    if ($user['password'] == md5($data['password'])) {
                        if ($user['level'] == 1) {
                            $updateToken = UserModel::get(['username' => $data['username']]);
                            $updateToken->loginToken = $data['token'];
                            $updateToken->save();
                            Session::set('token', $data['token']);
                            Session::set('admin', $user['username']);//保存会话
                            Cookie::set('token', $data['token']);
                            $this->redirect('/admin/t', 302);
                        } else {
                            $this->assign('error', '无效用户');
                        }
                    } else {
                        $this->assign('error', '密码错误，请重新输入');
                    }
                }
            }
            return $this->fetch('admin/login');
        } else {
            $this->redirect('/admin/t', 302);
        }
    }

    public function page()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $pageList = PageModel::all();
            $this->assign('pageList', $pageList);
            return $this->fetch('admin/page');
        } else {
            $this->error('管理员未登录');
        }
    }

    public function user()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $userList = UserModel::all();
            $this->assign('userList', $userList);
            return $this->fetch('admin/user');
        } else {
            $this->error('管理员未登录');
        }
    }

    public function node()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $nodeList = NodeModel::all();
            $this->assign('nodeList', $nodeList);
            return $this->fetch('admin/node');
        } else {
            $this->error('管理员未登录');
        }
    }

    public function starTopic()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $topic = TopicModel::get($id);
            if ($topic) {
                $topic->star = 1;
                $topic->save();
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function cancelTopic()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $topic = TopicModel::get($id);
            if ($topic) {
                $topic->star = 0;
                $topic->save();
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function deleteTopic()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $topic = TopicModel::get($id);
            if ($topic) {
                $topic->delete();
                ReplyModel::destroy(['topicId' => $id]);
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条话题数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function deleteReply()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $reply = ReplyModel::get($id);
            if ($reply) {
                $reply->delete();
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条回复数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function deleteNode()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $node = NodeModel::get($id);
            if ($node) {
                $node->delete();
                TopicModel::destroy(['node' => $id]);
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条回复数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function addNode()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $name = input('get.node-name');
            $nickname = input('get.node-nickname');
            $node = new NodeModel();
            $node->data([
                'name' => $name,
                'nickname' => $nickname
            ]);
            $node->save();
            $this->redirect('/admin/n', 302);
        } else {
            $this->redirect('/admin/n', 302);
        }
    }

    public function addAdmin()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $user = UserModel::get($id);
            if ($user) {
                $user->level = 1;
                $user->save();
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条用户数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function deleteAdmin()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $user = UserModel::get($id);
            if ($user) {
                $user->level = 0;
                $user->save();
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条用户数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function addPage()
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $name = input('post.page-name');
            $alias = input('post.page-alias');
            $content = input('post.page-content');
            $page = new PageModel();
            $page->data([
                'title' => $name,
                'alias' => $alias,
                'content' => $content,
                'creatTime' => time()
            ]);
            $page->save();
            $this->redirect('/admin/p', 302);
        } else {
            $this->redirect('/admin/p', 302);
        }
    }

    public function deletePage()
    {
        $code = array();
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $id = input('get.id');
            $page = PageModel::get($id);
            if ($page) {
                $page->delete();
                $code['code'] = 200;
            } else {
                $code['code'] = 400;
                $code['msg'] = '没有此条文章数据';
            }
        } else {
            $code['code'] = 400;
            $code['msg'] = '暂无操作权限';
        }

        echo json_encode($code);
    }

    public function changePage($id)
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            $page = PageModel::get($id);
            if (Request::instance()->isPost()) {
                $post = Request::instance()->post();
                $page->title = $post['page-name'];
                $page->alias = $post['page-alias'];
                $page->content = $post['page-content'];
                $page->creatTime = time();
                $page->save();
                $this->redirect('/admin/p', 302);
            }
            $this->assign('pageData', $page);
            return $this->fetch('admin/change');
        } else {
            $this->error('暂无操作权限。');
        }
    }

    public function reply($id = '')
    {
        if (Hook::exec('app\\index\\behavior\\CheckAdmin', 'run')) {
            if ($id == '') {
                $replyList = ReplyModel::all();
            } else {
                $replyList = ReplyModel::all(['topicId' => $id]);
            }
            $this->assign('replyList', $replyList);
            return $this->fetch('admin/reply');
        } else {
            $this->error('管理员未登录');
        }
    }
}

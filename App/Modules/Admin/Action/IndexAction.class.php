<?php

class IndexAction extends Action{
    public function index(){
        if (IS_POST) {
            $name = I('post.name');
            $password = I('post.password');
            $data = D('Admin')->where(array('name' => $name, 'password' => sha1($password)))->find();
            if ($data) {
                D('Admin')->where(array('name' => $name, 'password' => sha1($password)))->setField('login_time', time());
                $this->success('登录成功', __URL__.'/welcome');
                $_SESSION['aid'] = $data['id'];
                return;
            } else {
                $this->error('账号或密码错误');
            }
        }
        $this->display();
    }

    public function logout(){
        session(null);
        $this->success('退出成功', __ROOT__.'/Admin');
    }

    public function welcome(){
        if(empty($_SESSION['aid'])){
            $this->error('请登录后再操作');
            $this->redirect('/Admin');
        }
        $data = D('Admin')->find();
        $this->assign('admin', $data);
        $this->display();
    }

}
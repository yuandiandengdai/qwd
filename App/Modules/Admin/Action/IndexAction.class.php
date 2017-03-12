<?php

class IndexAction extends Action{
    public function index(){
        if (IS_POST) {
            $name = I('post.name');
            $password = I('post.password');
            $data = D('Admin')->where(array('name' => $name, 'password' => sha1($password)))->find();
            if ($data) {
                $this->success('登录成功', __URL__.'/welcome');
                session('aid', $data['id']);
                return;
            } else {
                $this->error('账号或密码错误');
            }
        }
        $this->display();
    }

    public function logout(){
        session(null);
        $this->redirect('/Admin');
    }

    public function welcome(){
        $this->display();
    }

}
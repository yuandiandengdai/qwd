<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 17:07
 */
class MemberAction extends Action{
    public function index(){
        $this->display();
    }

    public function login(){
        $email = I('post.email');
        $password = I('post.password');
        if (IS_POST) {
            $condition['email'] = $email;
            $data = D('Users')->field('id,name,email,password_digest,activated,status')->where($condition)->find();
            if (!$data) {
                $this->error('账号不存在！');
            } elseif ($data['password_digest'] != SHA1($password)) {
                $this->error('密码错误!');
            } elseif ($data['activated'] != 1) {
                $this->error('账户未激活!');
            } else {
                var_dump(D('Users')->relation(true)->find($data['id']));
                session('uid', $data['id']);
                session('user_name', $data['name']);
//                $this->success('登录成功！', __ROOT__.'/Member');
            }
        } elseif (IS_GET) {
            $this->display();
        }
    }

    public function logout(){
        session(null);
        $this->redirect('/');
    }

    public function register(){
        $user = new UsersModel();
        if (IS_POST) {
            if (!filter_var(I('post.email'), FILTER_VALIDATE_EMAIL)) {
                $this->error('邮件格式错误');
            } elseif (I('post.p1') != I('post.p2')) {
                $this->error('两次输入的密码不匹配');
            } else {
                $user->name = 'kkkkk';
                $user->add();

//                if($uid){
//                    session('uid', $uid);
//                    var_dump($uid);
//                    $this->success('注册成功',  __ROOT__.'/Member');
//                    return;
//                }else{
//                    $this->error('注册失败');
//                    return;
//                }
            }
        }
        $this->display();
    }

    public function forgetPassword(){
        $this->display();
    }

    public function updateInfo(){

        $this->display();
    }

    public function test(){
        $res = D('Test')->add(array(
            'name'=>'fffrrrf'
        ));
        var_dump($res);
    }
}
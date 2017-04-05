<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 17:07
 */
class MemberAction extends Action{
    public function index(){
//        var_dump(D('Member')->relation(true)->where(array('id' => $_SESSION['uid']))->find());
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        var_dump($_SESSION['did']);
        $this->display();
    }

    /**
     * 玩家登录
     */
    public function login(){
        $email = I('post.email');
        $password = I('post.password');
        if(IS_POST){
            if(session('verify') != md5(I('post.verify'))) $this->error('验证码错误');
            $condition['email'] = $email;
            $data = D('Member')->field('id,name,email,password,status,activate')->where($condition)->find();
            if(!$data){
                $this->error('账号不存在');
            }elseif($data['password'] != sha1($password)){
                $this->error('密码错误');
            }elseif($data['activate'] != 2){
                $this->error('账户未激活，请前往注册邮件中激活账号');
            }else{
                $_SESSION['uid'] = $data['id']; //------------玩家id-------------
                $_SESSION['user_name'] = $data['name']; //------------玩家姓名-------------
                $this->success('登录成功', __ROOT__ . '/Member');
            }
        }elseif(IS_GET){
            $this->display();
        }
    }

    /**
     * 退出
     */
    public function logout(){
        $time = time() - $_SESSION['time_did']; //如果用户进入房间后时间小于规定时间内退出，清除记录
        if($time < 60){
            D('Desk')->where('id=%d', $_SESSION['did'])->setDec('number');
            $member = D('Desk')->field('member_one,member_two,member_three')->where('id=%d', $_SESSION['did'])->find();
            foreach($member as $key => $value) {
                if($value == $_SESSION['user_name']){
                    D('Desk')->where('id=%d', $_SESSION['did'])->setField($key, '');
                }
            }
        }
        session(null);
        session_unset();
        session_destroy(); //清除所有的session
        $this->success('退出成功', '/');
    }

    /**
     * 注册
     */
    public function register(){
        if(IS_POST){
            $email = I('post.email');
            $name = I('post.name');
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $this->error('邮件格式错误');
            }elseif(I('post.p1') != I('post.p2')){
                $this->error('两次输入的密码不匹配');
            }else{
                $member = D('Member');
                $data['name'] = $name;
                $data['email'] = $email;
                $data['password'] = sha1(I('post.p1'));
                $data['create_at'] = NOW_TIME;
                $uid = $member->add($data);
                if($uid){
                    $token = uniqid(rand(), true);    //23位随机令牌
                    $activeToken = md5($token);
                    date_default_timezone_set("Asia/Shanghai");
                    $url = "http://qwd.com/Member/activeMember" . "?x={$email}" . "&y={$token}";
                    $encode = urlencode($url);
                    $msg = <<<EOF
		亲爱的{$name},您好!<br/>
		欢迎您来到趣味竞猜游戏,您是第{$uid}位玩家<br/>
		请点击此链接激活帐号！30分钟内有效<br/>
		<a href="{$url}">{$encode}</a>
		<br/>
		如果点击此链接无反映，您可以将其复制到浏览器中来执行。
EOF;
                    sendMail($email, '账号激活', $msg);
                    $_SESSION['activeToken'] = $activeToken;
                    $this->success('注册成功，请前往邮件中激活账号', '/');
                    return;
                }else{
                    $this->error('注册失败');
                    return;
                }
            }
        }
        $this->display();
    }

    public function checkUsername(){
        $map['name'] = $this->_post('name');
        $count = D('Member')->where($map)->count('id');
        if($count){
            $this->ajaxReturn(402);
        }else{
            $this->ajaxReturn(200);
        }
    }

    public function checkEmail(){
        $map['email'] = $this->_post('email');
        $count = D('Member')->where($map)->count('id');
        if($count){
            $this->ajaxReturn(402);
        }else{
            $this->ajaxReturn(200);
        }
    }

    /**
     * 忘记密码
     */
    public function forgetPassword(){
        if(IS_POST){
            $email = I('post.email');
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->error('邮件格式错误');
            $member = D('Member')->where(array('email' => $email))->find();
            if(!$member){
                $this->error('账号不存在');
            }else{
                $token = uniqid(rand(), true);    //23位随机令牌
                $resetPasswordToken = md5($token);
                date_default_timezone_set("Asia/Shanghai");
                $url = "http://qwd.com/Member/getResetPasswordToken" . "?x={$email}" . "&y={$token}";
                $encode = urlencode($url);
                $msg = <<<EOF
        {$member['name']}，你好：<br/>
        如果你要重置你的密码，请点击下面的链接:<br/>
		<a href="{$url}">{$encode}</a><br/>
		如果点击此链接无反映，您可以将其复制到浏览器中来执行。<br/>
		此链接有效期为2个小时，请尽快操作。<br/>
		如果你没有请求密码重置，请忽略此邮件，你的密码将不会改变。
EOF;
                sendMail($email, '重置密码', $msg);
                $_SESSION['resetPasswordToken'] = $resetPasswordToken;
                $_SESSION['memberId'] = $member['id'];
                $this->success('邮件发送成功，请前往邮件中重置密码');
                return;
            }
        }
        $this->display();
    }

    public function getResetPasswordToken(){
        $token = I('get.y');
        if(md5($token) == $_SESSION['resetPasswordToken']){
            $this->redirect(__ROOT__ . 'Member/resetPassword');
        }else{
            $this->error('无效的激活链接');
        }
    }

    /**
     * 重置密码
     */
    public function resetPassword(){
        if(IS_POST){
            if(I('post.p1') != I('post.p2')) $this->error('两次输入的密码不匹配');
            $res = D('Member')->where(array('id' => $_SESSION['memberId']))->setField('password', sha1(I('post.p1')));
            if($res){
                $_SESSION['uid'] = $_SESSION['memberId'];
                $_SESSION['user_name'] = $res['name'];
                $this->success('重置密码成功', __ROOT__ . '/Member');
                return;
            }else{
                $this->error('重置密码失败');
            }
        }
        $this->display();
    }

    /**
     * 更新资料
     */
    public function updateInfo(){
        if(empty($_SESSION['uid'])){
            $this->error('非法操作');
            $this->redirect('/');
        }
        $member = D('Member')->field('name,email')->where(array('id' => $_SESSION['uid']))->find();
        $this->assign('member', $member);
        if(IS_POST){
            if(I('post.p1') != I('post.p2')) $this->error('两次输入的密码不匹配');
            $res = D('Member')->where(array('id' => $_SESSION['memberId']))->setField('password', sha1(I('post.p1')));
            if($res){
                $this->success('更新资料成功', __ROOT__ . '/Member');
                return;
            }else{
                $this->error('资料更新失败或没有修改');
            }
        }else{
            $this->display();
        }
    }

    /**
     * 激活账号
     */
    public function activeMember(){
        $email = I('get.x');
        $token = I('get.y');
        if(md5($token) == $_SESSION['activeToken']){
            $res = D('Member')->where(array('email' => $email))->setField('activate', 2);
            if($res){
                $condition['email'] = $email;
                $data = D('Member')->field('id,name,email,password,status,activate')->where($condition)->find();
                $_SESSION['uid'] = $data['id'];
                $_SESSION['user_name'] = $data['name'];
                $this->success('激活成功', __ROOT__ . '/Member');
            }else{
                $this->error('激活失败');
            }
        }else{
            $this->error('无效的激活链接');
        }
    }

    /**
     * 测试ueditor使用
     */
    public function test(){
        $this->display();
    }

    public function test1(){
        $this->display();
    }

    public function test2(){
        $this->display();
    }

    public function test8(){
        $this->display();
    }

    public function test4(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $dataPoints_1 = array(
            array("y" => 243, "label" => "France"),
            array("y" => 273, "label" => "Great Britain"),
            array("y" => 525, "label" => "Soviet Union"),
            array("y" => 1118, "label" => "USA")
        );
        echo 'data:' . json_encode($dataPoints_1) . "\n\n";
        @ob_flush();
        @flush();
    }

    public function test5(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $dataPoints_2 = array(
            array("y" => 272, "label" => "France"),
            array("y" => 299, "label" => "Great Britain"),
            array("y" => 419, "label" => "Soviet Union"),
            array("y" => 896, "label" => "USA")
        );
        echo 'data:' . json_encode($dataPoints_2) . "\n\n";
        @ob_flush();
        @flush();
    }

    public function test6(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $dataPoints_3 = array(
            array("y" => 307, "label" => "France"),
            array("y" => 301, "label" => "Great Britain"),
            array("y" => 392, "label" => "Soviet Union"),
            array("y" => 788, "label" => "USA")
        );
        echo 'data:' . json_encode($dataPoints_3) . "\n\n";
        @ob_flush();
        @flush();
    }
}
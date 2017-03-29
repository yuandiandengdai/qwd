<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 14:27
 */
class GameAction extends Action{
    public function index(){
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        var_dump($_SESSION['uid']);
        $data = D('Room')->select();
        if(IS_POST){
            $rid = I('post.rid');
            $_SESSION['rid'] = $rid;
            $this->success('正在前往' . $rid . '号房间......', __ROOT__ . '/Game/wait');
            return;
        }
        $this->assign('data', $data);
        $this->display();
    }

    public function wait(){
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $rid = $_SESSION['rid'];
        var_dump($_SESSION['did']);
        var_dump($_SESSION['rid']);
        var_dump($_SESSION['user_name']);
        var_dump($_SESSION['time_did']);
        var_dump(time() - $_SESSION['time_did']);
        $table = D('Desk')->select();
        $this->assign('rid', $rid);
        $this->assign('table', $table);
        $this->display();
    }

    public function desk(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        if(IS_POST){
            $id = $this->_post('id');
            if($id != $_SESSION['did']){  //房间人数减一，同时去掉用户所在上一房间的名字
                D('Desk')->where('id=%d', $_SESSION['did'])->setDec('number');
                $member = D('Desk')->field('member_one,member_two,member_three')->where('id=%d', $_SESSION['did'])->find();
                foreach($member as $key => $value) {
                    if($value == $_SESSION['user_name']){
                        D('Desk')->where('id=%d', $_SESSION['did'])->setField($key, '');
                    }
                }
            }
            $_SESSION['did'] = $id;  //session赋值给刚刚进入的房间号
            $_SESSION['clear_did'] = $id;  //session赋值给刚刚进入的房间号,用于清空房间信息
            $_SESSION['time_did'] = time();   //session赋值给时间，监测 30 分钟内如果房间没有满人，则清空$_SESSION['did']所在玩家的信息
            $number = D('Desk')->where('id=%d', $id)->getField('number'); //获取房间的人数
            $table = D('Desk')->find($_SESSION['did']);
            if($number == 0){
                if($table['member_one'] == ''){
                    $data['member_one'] = $_SESSION['user_name'];
                }elseif($table['member_two'] == ''){
                    $data['member_two'] = $_SESSION['user_name'];
                }elseif($table['member_three'] == ''){
                    $data['member_three'] = $_SESSION['user_name'];
                }
                $data['number'] = 1;
                D('Desk')->where('id=%d', $_SESSION['did'])->save($data);
            }elseif($number == 1){
                if($table['member_one'] == ''){
                    $data['member_one'] = $_SESSION['user_name'];
                }elseif($table['member_two'] == ''){
                    $data['member_two'] = $_SESSION['user_name'];
                }elseif($table['member_three'] == ''){
                    $data['member_three'] = $_SESSION['user_name'];
                }
                $data['number'] = 2;
                D('Desk')->where('id=%d', $_SESSION['did'])->save($data);
            }elseif($number == 2){
                if($table['member_one'] == ''){
                    $data['member_one'] = $_SESSION['user_name'];
                }elseif($table['member_two'] == ''){
                    $data['member_two'] = $_SESSION['user_name'];
                }elseif($table['member_three'] == ''){
                    $data['member_three'] = $_SESSION['user_name'];
                }
                $data['number'] = 3;
                D('Desk')->where('id=%d', $_SESSION['did'])->save($data);
            }
        }
        $time = time() - $_SESSION['time_did'];
        $desk = D('Desk')->find($_SESSION['did']);
        if(($time > 60) && ($desk['number'] != 3)){
            D('Desk')->where('id=%d', $_SESSION['clear_did'])->setDec('number');
            $member = D('Desk')->field('member_one,member_two,member_three')->where('id=%d', $_SESSION['clear_did'])->find();
            foreach($member as $key => $value) {
                if($value == $_SESSION['user_name']){
                    D('Desk')->where('id=%d', $_SESSION['clear_did'])->setField($key, '');
                }
            }
            unset($_SESSION['clear_did']); // 释放玩家当时所在房间号
        }

        $data = D('Desk')->select();
        echo 'data:' . json_encode($data) . "\n\n";
        @ob_flush();
        @flush();
    }

    public function question(){
        global $qid;
        $room = D('Room')->where(array('id' => $_SESSION['rid']))->find(); //房间
        $number = $room['number'];
        $length = strlen($number);
        $question = D('Question')->order("rand()")->limit($length)->select();
        $counter = count($question);
        foreach($question as $value) { //将每次取出的题库题号取出来，用","分隔，存入到数据库中，可保证同时在这桌子的玩家看到的题目是一样的
            if($value == $question[$counter - 1]){
                $str = $value['id'];
            }else{
                $str = $value['id'] . ",";
            }
            $qid .= $str;
        }
        $res = D('DeskQuestion')->where('id=%d', $_SESSION['did'])->setField('question', $qid);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function hall(){
        global $qid;
        $question = array();
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $room = D('Room')->where(array('id' => $_SESSION['rid']))->find(); //房间
        if($this->question()){
            $qid = D('DeskQuestion')->where('id=%d', $_SESSION['did'])->getField('question');
        }
        $arr = explode(",", $qid); //分隔字符串，得到题库
        foreach($arr as $a) {
            $question[] = D('Question')->find($a);
        }
        $this->assign('room', $room);
        $this->assign('question', $question);
        $this->display();
    }

    public function getQuestion(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        echo 'data:' . json_encode($_SESSION['question'], JSON_UNESCAPED_UNICODE) . "\n\n";
        @ob_flush();
        @flush();
    }

    public function check(){
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $memberAnswer = I('get.memberAnswer');
        $qid = I('get.qid');
        $currentItem = I('get.c');
        $number = D('Room')->field('number')->where(array('id' => $_SESSION['rid']))->find();
        $answer = D('Question')->field('answer')->find($qid);
        if($memberAnswer == $answer['answer']){
            if($currentItem < strlen($number['number'])){
                $numberto = formatNumber($number['number'], $currentItem);
                echo createResponseJson(2, '回答正确，再接再厉！', $numberto);
            }else{
                $room = D('Room');
                $data['onwer'] = $_SESSION['user_name'];
                $data['onwertime'] = date('Y-m-d H:i:s', NOW_TIME);
                $room->where(array('id' => $_SESSION['rid']))->save($data); // 根据条件保存修改的数据
                echo createResponseJson(3, '恭喜你赢得本局比赛！', $number['number']);
            }
        }else{
            echo createResponseJson(4, '回答错误，继续努力！', '');
        }
    }

    public function memberInfo(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $data = D('Desk')->find($_SESSION['did']);
        echo 'data:' . json_encode($data) . "\n\n";
        @ob_flush();
        @flush();
    }


}
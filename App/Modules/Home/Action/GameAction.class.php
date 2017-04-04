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
            $_SESSION['rid'] = $rid; //------------玩家进入房间的id-------------
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

    /**
     * 桌子处理方法
     */
    public function desk(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        if(IS_POST){
            $id = $this->_post('id');
            if($id != $_SESSION['did']){  //房间人数减一，同时去掉用户所在上一房间的名字
                D('Desk')->where('id=%d', $_SESSION['did'])->setDec('number');
                $member = D('Desk')->field('member_one,member_two,member_three')->where('id=%d', $_SESSION['did'])->find();
                foreach($member as $key => $value){
                    if($value == $_SESSION['user_name']){
                        D('Desk')->where('id=%d', $_SESSION['did'])->setField($key, '');
                    }
                }
            }
            $_SESSION['question'] = $id; //session赋值给刚刚进入的房间号,用于清空题库的操作信息,避免再次生成题库
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
            foreach($member as $key => $value){
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
        foreach($question as $value){ //将每次取出的题库题号取出来，用","分隔，存入到数据库中，可保证同时在这桌子的玩家看到的题目是一样的
            if($value == $question[$counter - 1]){
                $str = $value['id'];
            }else{
                $str = $value['id'] . ",";
            }
            $qid .= $str;
        }
        $question_id = D('DeskQuestion')->where('id=%d', $_SESSION['question'])->getField('question');
        if(empty($question_id)){
            D('DeskQuestion')->where('id=%d', $_SESSION['question'])->setField('question', $qid);
            D('Desk')->where('id=%d', $_SESSION['did'])->setField('question_counter', $length);
        }
        return true;
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
        foreach($arr as $a){
            $question[] = D('Question')->find($a);
        }
        var_dump($_SESSION['did']);
        $this->assign('room', $room);
        $this->assign('table', $_SESSION['did']);
        $this->assign('question', $question);
        $this->display();
    }

    public function check(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $number = D('Room')->where(array('id' => $_SESSION['rid']))->getField('number');
        if(IS_GET){
            $memberAnswer = I('get.memberAnswer');
            $qid = I('get.qid');
            $currentItem = I('get.c');
            $answer = D('Question')->field('answer')->find($qid);
            if($memberAnswer == $answer['answer']){
                if($currentItem < strlen($number)){
                    $numberto = formatNumber($number, $currentItem);
                    D('Desk')->where('id=%d', $_SESSION['did'])->setDec('question_counter');
                    D('Desk')->where('id=%d', $_SESSION['did'])->setInc('qid'); //记录qid
                    echo createResponseJson(2, '回答正确，再接再厉！', $numberto);
                }else{
                    $room = D('Room');
                    $data['onwer'] = $_SESSION['user_name'];
                    $data['onwertime'] = date('Y-m-d H:i:s', NOW_TIME);
                    $room->where(array('id' => $_SESSION['rid']))->save($data); // 根据条件保存修改的数据
                    D('Desk')->where('id=%d', $_SESSION['did'])->setField('question_counter', 0); //游戏结束，未答题数清空
                    echo createResponseJson(3, '恭喜你赢得本局比赛！', $number);
                }
            }else{
                echo createResponseJson(4, '回答错误，继续努力！', '');
            }
        }
    }

    /**
     * 玩家答题信息统计
     */
    public function information(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $desk = D('Desk')->where(array('id' => $_SESSION['did']))->find(); //房间
        $number = intval($desk['question_counter']); //得到的是字符串，强转int
        $dataPoints = array(
            array("y" => $number, "label" => "本桌未答"),
            array("y" => 2, "label" => "{$desk['member_one']}答对"),
            array("y" => 4, "label" => "{$desk['member_two']}答对"),
            array("y" => 1, "label" => "{$desk['member_three']}答对"),
            array("y" => 5, "label" => "赢家答对总数")
        );
        echo 'data:' . json_encode($dataPoints, JSON_UNESCAPED_UNICODE) . "\n\n";
        @ob_flush();
        @flush();
        sleep(3);
    }

    public function questionId(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $id = D('Desk')->where(array('id' => $_SESSION['did']))->getField('qid');
        echo 'data:' . intval($id) . "\n\n";
        @ob_flush();
        @flush();
        sleep(3);
    }

}
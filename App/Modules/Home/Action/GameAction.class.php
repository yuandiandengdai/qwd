<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 14:27
 */
class GameAction extends Action{

    /**
     * 游戏选择房间
     */
    public function index(){
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $data = D('Room')->select();
        if(IS_POST){
            $rid = I('post.rid');
            D('Member')->where(array('id' => $_SESSION['uid']))->setField('rid', $rid);
            $_SESSION['rid'] = $rid; //------------玩家进入房间的id-------------
            $this->success('正在前往' . $rid . '号房间......', __ROOT__ . '/Game/wait');
            return;
        }
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 游戏选择桌子
     */
    public function wait(){
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $rid = $_SESSION['rid'];
        $tables = D('Tables')->select(); //获取桌子表的数据
        $this->assign('rid', $rid);
        $this->assign('tables', $tables);
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
            $id = $this->_post('id'); //接受玩家选择桌子号id
            D('Member')->where(array('id' => $_SESSION['uid']))->setField('tid', $id);
            D('Member')->where(array('id' => $_SESSION['uid']))->setField('add_time', time());
            if($id != $_SESSION['tid']){  //先判断玩家进入的房间和桌子号是否相等
                D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setDec('number'); //原来的房间和桌子人数减一
                $member = D('Desk')->field('member_one,member_two,member_three')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->find();
                foreach($member as $key => $value){ //清空原来玩家所在的记录用户名
                    if($value == $_SESSION['user_name']){
                        D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField($key, '');
                    }
                }
                $desk = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $id))->find(); //判断玩家选择的桌子和房间号是否在数据库中存在记录
                if($desk){ //如果有，新纪录--则更新插入新玩家的名字和更新桌子的在线人数
                    if($desk['number'] == 0){
                        if($desk['member_one'] == ''){
                            $data['member_one'] = $_SESSION['user_name'];
                        }elseif($desk['member_two'] == ''){
                            $data['member_two'] = $_SESSION['user_name'];
                        }elseif($desk['member_three'] == ''){
                            $data['member_three'] = $_SESSION['user_name'];
                        }
                        $data['number'] = 1;
                        D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $id))->save($data);
                    }elseif($desk['number'] == 1){
                        if($desk['member_one'] == ''){
                            $data['member_one'] = $_SESSION['user_name'];
                        }elseif($desk['member_two'] == ''){
                            $data['member_two'] = $_SESSION['user_name'];
                        }elseif($desk['member_three'] == ''){
                            $data['member_three'] = $_SESSION['user_name'];
                        }
                        $data['number'] = 2;
                        D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $id))->save($data);
                    }elseif($desk['number'] == 2){
                        if($desk['member_one'] == ''){
                            $data['member_one'] = $_SESSION['user_name'];
                        }elseif($desk['member_two'] == ''){
                            $data['member_two'] = $_SESSION['user_name'];
                        }elseif($desk['member_three'] == ''){
                            $data['member_three'] = $_SESSION['user_name'];
                        }
                        $data['number'] = 3;
                        D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $id))->save($data);
                    }
                }else{ //如果没有，就往数据库中插入
                    $desk = D('Desk');
                    $data['rid'] = $_SESSION['rid'];
                    $data['tid'] = $id;
                    $data['member_one'] = $_SESSION['user_name'];
                    $data['number'] = 1;
                    $desk->add($data);
                }
            }
            $_SESSION['tid'] = $id;  //session赋值给刚刚进入的房间号
            $_SESSION['clear_did'] = $id;  //session赋值给刚刚进入的房间号,用于清空房间信息
            $_SESSION['time_did'] = time();   //session赋值给时间，监测 30 分钟内如果房间没有满人，则清空$_SESSION['did']所在玩家的信息
        }
        $time = time() - $_SESSION['time_did'];
        $desk = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->find();
        if(($time > 60) && ($desk['number'] != 3)){
            D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setDec('number');
            $member = D('Desk')->field('member_one,member_two,member_three')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->find();
            foreach($member as $key => $value){
                if($value == $_SESSION['user_name']){
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField($key, '');
                }
            }
            unset($_SESSION['clear_did']); // 释放玩家当时所在房间号
        }

        $data = D('Desk')->where('rid=%d', $_SESSION['rid'])->order('tid ASC')->select();
        echo 'data:' . json_encode($data) . "\n\n";
        @ob_flush();
        @flush();
    }

    /**随机生成题库
     * @return bool
     */
    public function question(){
        global $qid;
        $room = D('Room')->where(array('id' => $_SESSION['rid']))->find(); //获取玩家进入房间的id
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
        $question_id = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('question');
        $numbers = formatNumber($number);
        if(empty($question_id)){
            D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('question', $qid); //记录全部题库id号
            D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('question_counter', $length); //记录当前的题目数量
            D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('numbers', $numbers); //记录卡号
        }
        return true;
    }

    /**
     * 游戏大厅，根据qid取得题目
     */
    public function hall(){
        global $qid;
        $question = array();
        if(empty($_SESSION['uid'])){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $room = D('Room')->where(array('id' => $_SESSION['rid']))->find(); //房间
        if($this->question()){
            $qid = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('question');
        }
        $arr = explode(",", $qid); //分隔字符串，得到题库
        foreach($arr as $a){
            $question[] = D('Question')->find($a);
        }
        $data = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->find();
        $this->assign('room', $room);
        $this->assign('data', $data);
        $this->assign('table', $_SESSION['tid']);
        $this->assign('question', $question);
        $this->display();
    }

    /**
     * 验证答案
     */
    public function check(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        global $id;
        global $winner;
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
            if($memberAnswer == $answer['answer']){ //回答正确
                $id = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('qid'); //记录qid
                if(intval($id) < (strlen($number) - 1)){
                    $numberto = formatNumber($number, $currentItem);
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setDec('question_counter'); //题目数量减一
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setInc('qid'); //记录当前的qid
                    $member = D('Desk')->field('member_one,member_two,member_three')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->find();
                    foreach($member as $key => $value){
                        if($value == $_SESSION['user_name']){
                            D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setInc($key . '_counter'); //记录玩家答对的题目数量
                        }
                    }
                    $id = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('qid'); //记录qid
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('numbers', formatNumber($number, $id)); //记录当前的卡号

                    D('Member')->where(array('id' => $_SESSION['uid']))->setInc('correct'); //玩家答对书加一

                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('message', "{$_SESSION['user_name']}" . '回答正确，再接再厉！');
                    echo createResponseJson(2, "{$_SESSION['user_name']}" . '回答正确，再接再厉！', $numberto);
                }else if(intval($id) == (strlen($number) - 1)){ //游戏结束
                    $counter = array();
                    $counter['one'] = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('member_one_counter');
                    $counter['two'] = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('member_two_counter');
                    $counter['three'] = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('member_three_counter');
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('winner_counter', max($counter)); //游戏结束，记录最多的赢数

                    $winner_counter = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('winner_counter');
                    $member = D('Desk')->field('member_one_counter,member_two_counter,member_three_counter')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->find();
                    foreach($member as $key => $value){
                        if($value == $winner_counter){
                            $winner = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField(substr($key, 0, strlen($key) - 8)); //截取字符串
                        }
                    }
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('winner', $winner); //游戏结束，记录最多的赢数玩家

                    D('Member')->where(array('name' => $winner))->setInc('win');

                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('question_counter', 0); //游戏结束，未答题数清空
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('question', ''); //游戏结束，清空题库
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('winner_time', time()); //游戏结束，清空题库
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('qid', 0); //游戏结束，当前的题号为0
                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('numbers', formatNumber($number, strlen($number))); //显示所有的卡号

                    D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('message', '本局比赛结束！');

                    echo createResponseJson(3, '本局比赛结束！', $number);
                }
            }else{ //回答错误
                D('Member')->where(array('id' => $_SESSION['uid']))->setInc('error');
                echo createResponseJson(4, "{$_SESSION['user_name']}" . '回答错误，继续努力！', '');
                D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->setField('message', "{$_SESSION['user_name']}" . '回答错误，继续努力！');

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
        $desk = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->find(); //房间
        $number = intval($desk['question_counter']); //得到的是字符串，强转int
        $dataPoints = array(
            array("y" => $number, "label" => "本桌未答"),
            array("y" => intval($desk['member_one_counter']), "label" => "{$desk['member_one']}答对"),
            array("y" => intval($desk['member_two_counter']), "label" => "{$desk['member_two']}答对"),
            array("y" => intval($desk['member_three_counter']), "label" => "{$desk['member_three']}答对"),
            array("y" => intval($desk['winner_counter']), "label" => "赢家答对总数")
        );
        echo 'data:' . json_encode($dataPoints, JSON_UNESCAPED_UNICODE) . "\n\n";
        @ob_flush();
        @flush();
    }

    /**
     * 获取题目id号
     */
    public function questionId(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $id = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('qid');
        echo 'data:' . intval($id) . "\n\n";
        @ob_flush();
        @flush();
    }

    /**
     * 获取房间卡号
     */
    public function numbers(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $numbers = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('numbers');
        echo 'data:' . $numbers . "\n\n";
        @ob_flush();
        @flush();
    }

    /**
     * 获取题目的数量
     */
    public function counter(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $id = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('question_counter');
        echo 'data:' . intval($id) . "\n\n";
        @ob_flush();
        @flush();
    }

    public function message(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $messageNew = D('Desk')->where(array('rid' => $_SESSION['rid'], 'tid' => $_SESSION['tid']))->getField('message');
        if(md5($_SESSION['message']) != md5($messageNew)){ //比对数据库信息是否做修改
            echo 'data:' . $messageNew . "\n\n";
            @ob_flush();
            @flush();
            $_SESSION['message'] = $messageNew;
        }
    }
}
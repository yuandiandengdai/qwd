<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 14:27
 */
class GameAction extends Action{
    public function index(){
        if(empty(session('uid'))){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        var_dump(session('uid'));
        $data = D('Room')->select();
        if(IS_POST){
            $rid = I('post.rid');
            session('rid', $rid);
            $this->success('正在前往' . $rid . '号房间......', __ROOT__ . '/Game/wait');
            return;
        }
        $this->assign('data', $data);
        $this->display();
    }

    public function wait(){
        if(empty(session('uid'))){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $rid = session('rid');
        var_dump(session('did'));
        var_dump(session('user_name'));
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
            if($id != session('did')){  //房间人数减一，同时去掉用户所在上一房间的名字
                D('Desk')->where('id=%d', session('did'))->setDec('number');
                $member = D('Desk')->field('member_one,member_two,member_three')->where('id=%d', session('did'))->find();
                foreach($member as $key => $value){
                    if($value == session('user_name')){
                        D('Desk')->where('id=%d', session('did'))->setField($key, '');
                    }
                }
            }
            session('did', $id);
            $number = D('Desk')->where('id=%d', $id)->getField('number'); //获取房间的人数
            if($number == 0){
                $data['member_one'] = session('user_name');
                $data['number'] = 1;
                D('Desk')->where('id=%d', session('did'))->save($data);
            }elseif($number == 1){
                $data['member_two'] = session('user_name');
                $data['number'] = 2;
                D('Desk')->where('id=%d', session('did'))->save($data);
            }elseif($number == 2){
                $data['member_three'] = session('user_name');
                $data['number'] = 3;
                D('Desk')->where('id=%d', session('did'))->save($data);
            }
        }

        $data = D('Desk')->find(session('did'));
        if(empty(session('did'))){
            $info = array(
                'status' => 300,
                'member' => '',
                'number' => 0,
            );
            echo 'data:' . json_encode($info) . "\n\n";
            @ob_flush();
            @flush();
        }else{
            $info = array(
                'status' => 200,
                'table' => $data['id'],
                'number' => $data['number'],
                'member_one' => $data['member_one'],
                'member_two' => $data['member_two'],
                'member_three' => $data['member_three'],
            );
            echo 'data:' . json_encode($info) . "\n\n";
            @ob_flush();
            @flush();
        }

    }

    public function hall(){
        if(empty(session('uid'))){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $room = D('Room')->where(array('id' => session('rid')))->find();
        $question = D('Question')->order("rand()")->limit(1)->select();
        $this->assign('room', $room);
        $this->assign('question', $question);
        $this->display();
    }

    public function check(){
        if(empty(session('uid'))){
            $this->error('请登录后再操作');
            $this->redirect('/');
        }
        $memberAnswer = I('get.memberAnswer');
        $qid = I('get.qid');
        $currentItem = I('get.c');
        $question = D('Question')->order("rand()")->limit(1)->select();
        $number = D('Room')->field('number')->where(array('id' => session('rid')))->find();
        $answer = D('Question')->field('answer')->find($qid);
        if($memberAnswer == $answer['answer']){
            if($currentItem < strlen($number['number'])){
                $numberto = formatNumber($number['number'], $currentItem);
                echo createResponseJson(2, '回答正确，再接再厉！', $numberto, $question[0]['question'], $question[0]['id']);
            }else{
                $room = D('Room');
                $data['onwer'] = session('user_name');
                $data['onwertime'] = date('Y-m-d H:i:s', NOW_TIME);
                $room->where(array('id' => session('rid')))->save($data); // 根据条件保存修改的数据
                echo createResponseJson(3, '恭喜你赢得本局比赛！', $number['number'], '', '');
            }
        }else{
            echo createResponseJson(4, '回答错误，继续努力！', '', '', '');
        }
    }
}
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
        $data = D('Room')->select();
        if (IS_POST) {
            $rid = I('post.rid');
            session('rid', $rid);
            $this->success('正在前往游戏大厅', __ROOT__.'/Game/hall');
            return;
        }
        $this->assign('data', $data);
        $this->display();
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
        if ($memberAnswer == $answer['answer']) {
            if ($currentItem < strlen($number['number'])) {
                $numberto = formatNumber($number['number'], $currentItem);
                echo createResponseJson(2, '回答正确，再接再厉！', $numberto, $question[0]['question'], $question[0]['id']);
            } else {
                $room = D('Room');
                $data['onwer'] = session('user_name');
                $data['onwertime'] = date('Y-m-d H:i:s', NOW_TIME);
                $room->where(array('id' => session('rid')))->save($data); // 根据条件保存修改的数据
                echo createResponseJson(3, '恭喜你赢得本局比赛！', $number['number'], '', '');
            }
        } else {
            echo createResponseJson(4, '回答错误，继续努力！', '', '', '');
        }
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 17:10
 */
class RankAction extends Action{
    public function index(){
        $counter = D('Member')->field('id,name,win')->order('win DESC')->limit(9)->select();
        $add_time = D('Member')->field('id,name,rid,add_time')->order('add_time DESC')->limit(9)->select();
        $room = D('Room')->field('id,onwer,count')->select();
        $this->assign('counter', $counter);
        $this->assign('add_time', $add_time);
        $this->assign('room', $room);
        $this->display();
    }

    public function counter(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $counter = D('Member')->field('id,name,win')->order('win DESC')->limit(9)->select();
        echo 'data:' . json_encode($counter) . "\n\n";
        @ob_flush();
        @flush();
    }

    public function time(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $add_time = D('Member')->field('id,name,rid,add_time')->order('add_time DESC')->limit(9)->select();
        foreach($add_time as $key => $val){
            $add_time[$key]['add_time'] = date('Y-m-d H:i:s', $add_time[$key]['add_time']);
        }
        echo 'data:' . json_encode($add_time) . "\n\n";
        @ob_flush();
        @flush();
    }

    public function room(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $room = D('Room')->field('id,onwer,count')->select();
        echo 'data:' . json_encode($room) . "\n\n";
        @ob_flush();
        @flush();
    }
}
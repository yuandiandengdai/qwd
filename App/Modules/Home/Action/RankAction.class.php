<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 17:10
 */
class RankAction extends Action{
    public function index(){
        $counter = D('Member')->field('name,win')->order('win DESC')->limit(9)->select();
        $add_time = D('Member')->field('name,rid,add_time')->order('add_time DESC')->limit(9)->select();
        $room = D('Room')->field('onwer,count')->select();
        $this->assign('counter', $counter);
        $this->assign('add_time', $add_time);
        $this->assign('room', $room);
        $this->display();
    }

    public function member(){
        header('Content-Type: text/event-stream');
        $data = D('Member')->field('name,add_time')->select();
        echo "data:" . json_encode($data) . "\n\n";
        @flush();
        @ob_flush();

    }
}
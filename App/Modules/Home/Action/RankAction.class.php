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
        $room = D('Desk')->field('id,rid,tid')->limit(9)->select();
        $this->assign('counter', $counter);
        $this->assign('add_time', $add_time);
        $this->assign('room', $room);
        $this->display();
    }

    /**
     * 领先排行列表
     */
    public function counter(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $counter = D('Member')->field('id,name,win')->order('win DESC')->limit(9)->select();
        echo 'data:' . json_encode($counter) . "\n\n";
        @ob_flush();
        @flush();
    }

    /**
     * 玩家状态列表
     */
    public function time(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $add_time = D('Member')->field('id,name,rid,tid,add_time')->order('add_time DESC')->limit(9)->select();
        foreach($add_time as $key => $val){
            $add_time[$key]['add_time'] = date('m-d H:i:s', $add_time[$key]['add_time']);
        }
        echo 'data:' . json_encode($add_time) . "\n\n";
        @ob_flush();
        @flush();
    }

    /**
     * 房间状态列表
     */
    public function room(){
        header("X-Accel-Buffering: no");
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        $room = D('Desk')->relation(true)->field('id,rid,tid,winner')->limit(9)->select();
        echo 'data:' . json_encode($room, JSON_UNESCAPED_UNICODE) . "\n\n";
        @ob_flush();
        @flush();
    }
}
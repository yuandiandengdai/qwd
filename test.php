<?php
/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-03-15
 * Time: 14:44
 */
echo md5(8432);
//117ffc1acd844e431a4b73f0867adae5

        if($number == 0){
            $data['member_one'] = session('user_name');
            $data['number'] = 1;
            D('Desk')->where('id=%d', $id)->save($data);
        }elseif($number == 1){
            $data['member_two'] = session('user_name');
            $data['number'] = 2;
            D('Desk')->where('id=%d', $id)->save($data);
        }elseif($number == 2){
            $data['member_three'] = session('user_name');
            $data['number'] = 3;
            D('Desk')->where('id=%d', $id)->save($data);
        }

$member_one = D('Desk')->where('id=%d', $id)->getField('member_one');
$member_two = D('Desk')->where('id=%d', $id)->getField('member_two');
$member_three = D('Desk')->where('id=%d', $id)->getField('member_three');

//            $this->ajaxReturn(json_encode($data)); //ajax提示信息

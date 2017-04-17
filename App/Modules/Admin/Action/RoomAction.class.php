<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 00:03
 */
class RoomAction extends Action{
    public function index(){
        if(empty($_SESSION['aid'])){
            $this->error('请登录后再操作');
            $this->redirect(__ROOT__.'/Admin');
        }
        if(IS_POST){
            $room = D('Room')->create();
            if($room['id']){//修改
                D('Room')->where('id=%d', $room['id'])->save($room);
                $this->success('修改成功');
                return;
            }else{//新增
                D('Room')->add($room);
                $this->success('新增成功');
                return;
            }
        }
        import('ORG.Util.Page');// 导入分页类
        $count = D('Room')->count();// 查询满足要求的总记录数
        $Page = new Page($count, 8);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = D('Room')->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('data', $data);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display(); // 输出模板
    }

    public function delete($id){
        $room = D('Room');
        $res = $room->where(array('id' => $id))->delete();
        if($res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}
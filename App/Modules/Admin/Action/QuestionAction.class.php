<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 00:05
 */
class QuestionAction extends Action{
    public function index(){
        if(empty(session('aid'))){
            $this->error('请登录后再操作');
            $this->redirect(__ROOT__.'/Admin');
        }
        if(IS_POST){
            $question = D('Question')->create();
            if($question['id']){//修改
                D('Question')->where('id=%d', $question['id'])->save($question);
                $this->success('修改成功');
                return;
            }else{//新增
                D('Question')->add($question);
                $this->success('新增成功');
                return;
            }
        }
        import('ORG.Util.Page');// 导入分页类
        $count = D('Question')->count();// 查询满足要求的总记录数
        $Page = new Page($count, 8);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = D('Question')->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('data', $data);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display(); // 输出模板
    }

    public function delete($id){
        $question = D('Question');
        $res = $question->where(array('id' => $id))->delete();
        if($res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}
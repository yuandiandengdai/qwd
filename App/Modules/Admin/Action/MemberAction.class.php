<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 00:06
 */
class MemberAction extends Action{
    public function index(){
        import('ORG.Util.Page');// 导入分页类
        $count = D('Member')->count();// 查询满足要求的总记录数
        $Page = new Page($count, 8);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = D('Member')->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('data', $data);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display(); // 输出模板
    }
}
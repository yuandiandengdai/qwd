<?php
class IndexAction extends Action{
	public function index(){
		$this->title = '后台首页';
		$this->display();
	}
}
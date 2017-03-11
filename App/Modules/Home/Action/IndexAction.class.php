<?php
class IndexAction extends Action{
	public function index(){
		$this->title = '前台首页';
		$this->display();
	}
}
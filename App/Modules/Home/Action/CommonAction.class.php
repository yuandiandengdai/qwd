<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-21
 * Time: 20:16
 */
class CommonAction extends Action{
    public function verifyCode(){
        import('ORG.Util.Image');
        ob_clean();
        Image::buildImageVerify(4, 1, 'png', 90, 34);
    }

    public function verify(){
        if(session('verify') == md5($this->_post('verify'))){
            $this->ajaxReturn(200);
        }else{
            $this->ajaxReturn(300);
        }
    }
}
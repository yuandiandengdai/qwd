<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 22:04
 */
class AdminModel extends Model{
    protected $trueTableName = 'admin';
    protected $fields = array('id', 'name', 'password', 'login_time');

}
<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-4-23
 * Time: 00:35
 */
class MessageModel extends Model{
    protected $trueTableName = 'message';
    protected $fields = array('id', 'uid', 'name', 'message');
}
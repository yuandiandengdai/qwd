<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 18:15
 */
class RoomModel extends Model{
    protected $trueTableName = 'room';
    protected $fields = array('id', 'name', 'number', 'count', 'onwertime', 'onwer', 'addtime', 'status');
}
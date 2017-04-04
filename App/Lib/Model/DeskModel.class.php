<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-24
 * Time: 00:56
 */
class DeskModel extends Model{
    protected $trueTableName = 'desk';
    protected $fields = array('id', 'member_one', 'member_two', 'member_three', 'number', 'question_counter', 'qid');
}
<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-24
 * Time: 00:56
 */
class DeskModel extends Model{
    protected $trueTableName = 'desk';
    protected $fields = array('id', 'rid', 'tid', 'member_one', 'member_two', 'member_three', 'number',
        'member_one_counter', 'member_two_counter', 'member_three_counter',  'question', 'question_counter', 'qid',
        'numbers', 'winner', 'winner_counter');
}
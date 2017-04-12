<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-24
 * Time: 00:56
 */
class DeskModel extends RelationModel{
    protected $trueTableName = 'desk';
    protected $fields = array('id', 'rid', 'tid', 'member_one', 'member_two', 'member_three', 'number',
        'member_one_counter', 'member_two_counter', 'member_three_counter', 'question', 'question_counter', 'qid',
        'numbers', 'winner', 'winner_counter', 'winner_time');

    protected $_link = array(
        'Room' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'Room',
            'mapping_fields' => 'count',
            'foreign_key' => 'rid',
            'mapping_name' => 'room',
            'mapping_limit' => 1
        )
    );
}
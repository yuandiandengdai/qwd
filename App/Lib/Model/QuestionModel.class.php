<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-12
 * Time: 17:04
 */
class QuestionModel extends Model{
    protected $trueTableName = 'question';
    protected $fields = array('id', 'question', 'answer_text', 'answer', 'status');
}
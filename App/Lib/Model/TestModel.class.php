<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 18:23
 */
class TestModel extends Model{
    protected $trueTableName = 'test';
    protected $fields = array(
        'id', 'name', 'email', 'age'
    );
}
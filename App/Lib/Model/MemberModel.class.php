<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 21:37
 */
class MemberModel extends RelationModel{
    protected $trueTableName = 'member';
    protected $fields = array('id', 'rid', 'name', 'email', 'password', 'create_at',
        'admin', 'status', 'activate', 'add_time', 'win');

    protected $_link = array(
        'Room' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'Room',
            'foreign_key' => 'rid',
        )
    );

    protected $_scope = array(
        'normal' => array(
            'where' => array('status' => 1),
        ),
    );
}
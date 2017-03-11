<?php

/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 17:38
 */
class UsersModel extends RelationModel{
    protected $trueTableName = 'users';
    protected $fields = array('name','email','created_at','updated_at','password_digest', 'remember_digest',
        'admin', 'activation_digest', 'activated', 'activated_at', 'reset_digest', 'reset_sent_at', 'status', 'rid',
        'addtime', 'onwercount');

    protected $_link = array(
        'Room' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'Room',
            'foreign_key' => 'rid',
        )
    );
}
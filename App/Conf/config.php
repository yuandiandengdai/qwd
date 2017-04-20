<?php
return array(
    /* 网站前后台分组模块设置 */
    'APP_GROUP_MODE' => 1,
    'APP_GROUP_LIST' => 'Home,Admin',//分组
    'DEFAULT_APP' => 'Home',
    /* 数据库设置 */
    'DB_TYPE' => 'mysql',     // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'qwd',          // 数据库名
    'DB_USER' => 'root',      // 用户名
    'DB_PWD' => 'root',          // 密码
    'DB_PORT' => '3306',        // 端口
    /* url模式，去掉index.php */
    'URL_MODEL' => 2,
    'URL_CASE_INSENSITIVE' =>true,
    /* 邮件配置 */
    'SMTP_MAIL' => array(
        'username' => '1551422971@qq.com',
        'password' => 'kequvrxxfnbmbaah',
    ),
    /* 公共的提示模板文件 */
    'TMPL_ACTION_ERROR' => 'Public/html/tip.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public/html/tip.html', // 默认成功跳转对应的模板文件
);
?>
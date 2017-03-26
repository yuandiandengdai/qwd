<?php
/**
 * Created by PhpStorm.
 * User: yuandian
 * Date: 2017-3-11
 * Time: 16:36
 */

/**
 * @param $to 邮件接收者
 * @param $title 发送邮件的标题
 * @param $content 发送邮件的内容
 * @return bool 发送状态
 */
function sendMail($to, $title, $content){
    vendor('Zend.PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.qq.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    date_default_timezone_set('Asia/Shanghai');//设定时区东八区
    $mail->CharSet = 'utf-8';
    $mail->Username = C('SMTP_MAIL')['username'];
    $mail->Password = C('SMTP_MAIL')['password'];
    $mail->From = C('SMTP_MAIL')['username'];
    $mail->isHTML(true);
    $mail->addAddress($to);
    $mail->FromName = '趣味竞猜';
    $mail->Subject = $title;
    $mail->Body = $content;
    $status = $mail->send();
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function createResponseJson($code, $msg, $number){
    $ret = array();
    $ret['sts'] = $code;
    $ret['msg'] = $msg;
    $ret['num'] = $number;
    return json_encode($ret, JSON_UNESCAPED_UNICODE);
}

function formatNumber($number, $where = 0){
    $len = strlen($number);
    $format = '';
    for($i = 0; $i < $len - $where; $i++){
        $format .= 'X';
    }
    return substr($number, 0, $where).$format;
}
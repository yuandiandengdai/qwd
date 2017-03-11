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
    //实例化PHPMailer核心类
    $mail = new PHPMailer();

    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
//    $mail->SMTPDebug = 1;

    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();

    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;

    //链接qq域名邮箱的服务器地址
    $mail->Host = 'smtp.qq.com';

    //设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';

    //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
    $mail->Port = 465;
    date_default_timezone_set('Asia/Shanghai');//设定时区东八区

    //设置发送的邮件的编码 据说utf8在某些客户端收信下会乱码
    $mail->CharSet = 'utf-8';

    //smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username = C('SMTP_MAIL')['username'];
    ;

    //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
    $mail->Password =  C('SMTP_MAIL')['password'];

    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From = C('SMTP_MAIL')['username'];

    //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true);

    //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称
    // 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    $mail->addAddress($to);

    //添加多个收件人 则多次调用方法即可
    // $mail->addAddress('xxx@163.com','lsgo在线通知');

    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = '趣味竞猜';

    //添加该邮件的主题
    $mail->Subject = $title;

    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = $content;
    $status = $mail->send();
    if ($status) {
        return true;
    } else {
        return false;
    }
}

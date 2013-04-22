<?php
/**
 * @package: PMAIL.PLUGIN
 * @file   : DisMailPlg.class.php
 * @abstract  : 发送电子邮件
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisMailPlg extends DisObject
{
    static $from;

    static function send($reciever, $title, $text, $head = null)
    {
        $subject = "=?UTF-8?B?".base64_encode($title)."?=";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if( $head )
            $headers .= $head."\r\n";
        $headers .= "From: " . self::$from;
        mail($reciever, $subject, $text, $headers);
    }

    static function send_email($email, $title, $text, $head = null)
    {
        $_url = DisConfigAttr::$app['url'];
        $logo = 'http://'.$_url.'/'.DisConfigAttr::$app['logo'];
        $logo = '<div style="height:30px;"><img style="height:30px;" src="'.$logo.'"/></div>';
        $bord = '<div style="background-color:#cacaca;height:2px;width:500px;"></div>';
        $foot = '<div style="text-align:center"><p>'.DisConfigAttr::$app['name'].'：'.DisConfigAttr::$app['goal'].'！</p></div>';
        $cont = '<div style="width:500px;margin:0 auto;padding:10px 20px;">'.$text.$bord.$foot.'</div>';
        self::send($email, $title, $cont);
    }

    static function send_invite_email($email, $code, $username)
    {
        if( !email_check($email) )
            throw new DisException('邮箱格式不正确');

        $title = $username.' 邀请你加入' . DisConfigAttr::$app['name'];
        $_url = 'http://'.DisConfigAttr::$app['url'].'/register?invi='.$code;
        $text = '<h2>你的好友 '.$username.' 邀请你加入 ' . DisConfigAttr::$app['name'].'</h2>'
            ."<br>请点击以下连接完成注册：<p><a href=\"$_url\" target=\"_blank\">$_url</a></p>";
        DisMailPlg::send_email($email, $title, $text);

//        $v['url'] = $_url;
//        $v['title'] = $title;
//        $v['text'] = $text;
//        soObject::print_array($v);
    }

    static function send_veri_email($email, $user_id, $username)
    {
        if( !email_check($email) )
            throw new DisException('邮箱格式不正确');

        $_key = DisUserCtrl::verify_code($user_id);
        $_url = 'http://'.DisConfigAttr::$app['url']."/regi?id=".$user_id."&verify=".$_key;
        $text = "<h3>Hi, ".$username."，欢迎注册".DisConfigAttr::$app['name']."！</h3>"
                ."<p>请在点击以下链接激活帐号：</p>"
                .'<p><a href="'.$_url.'" target="_blank">'.$_url.'</a></p>'
                ."<p>如果浏览器不能自动打开，请把地址复制到浏览器地址栏中打开。</p>";
        $title = '['.DisConfigAttr::$app['name'].'] 帐号激活';
        DisMailPlg::send_email($email, $title, $text);
//        echo "邮件发送成功！";
    }
}

DisMailPlg::$from = '<zhujiyu@139.com>';
//pmMail::$from = pmConfig::$app['name'].'<zhujiyu@139.com>';
//pmMail::$from = '"'.pmConfig::$app['name'].'" <zhujiyu@139.com>';
?>

<?php
/**
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once 'common.inc.php';

ob_start();
try
{
    $gSmarty = init_smarty();
    $val = preg_match('/regi\/([0-9a-z]{12})$/i', $url, $matches);
    if( $val )
    {
        header('Location: ../regi?invi='.$matches[1]); exit;
    }

    $p = $_GET['p'] ? $_GET['p'] : $_POST['p'];
    $intr = $_GET['intr'] ? $_GET['intr'] : $_POST['intr'];
    $invi = $_GET['invi'] ? $_GET['invi'] : $_POST['invi'];
    $veri = $_GET['veri'] ? $_GET['veri'] : $_POST['veri'];

    if( isset($_SESSION['userId']) && $_SESSION['userId'] > 0 && DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        $user_id = $_SESSION['userId'];
        $user = DisUserCtrl::user($user_id);
        $gSmarty->assign("user", $user->info());
        DisUserCtrl::set_inline($user_id);
    }
    else
        $user_id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
//    $file = "pmail.register.tpl";
    $file = "register.page.tpl";

    if( $invi )
    {
        $invite = DisInviteCtrl::check($invi);
        $intr = $invite['user_id'];
        $email = $invite['email'];
        $gSmarty->assign("invite", $invite);
        $gSmarty->assign("email", $email);
    }

    if( $intr )
    {
        $intr_user = DisUserCtrl::get_data ($intr);
        $gSmarty->assign("introUser", $intr_user);
    }

    if( isset($_POST['register']) || $p == 'register' )
    {
        $email = $_POST['email'] ? $_POST['email'] : $_GET['email'];
        $user = DisUserCtrl::register($_POST['uname'], md5($_POST['pword']), $email);
        $user_id = $user->ID;

        // 注册完成后 继续激活操作！
        $_SESSION['userId'] = $user_id;
        if( $intr )
            $user->follow((int)$intr);
        DisUserCtrl::set_inline($user_id);

        $user_data = $user->info();
        $user_data['avatar'] = array('ID'=>0, 'small'=>'css/logo/avatar_s.jpg', 'big'=>'css/logo/avatar_b.jpg');
        $gSmarty->assign("user", $user_data);

        if( $_POST['invi'] )
        {
            DisInviteCtrl::update($_POST['invi'], $user_id);
            $veri = DisUserCtrl::verify_code($user_id);
        }
        else
        {
            DisMailPlg::send_veri_email($email, $user_id, $user->attr('username'));
        }
        $p = 'tag';
    }

    if( isset($_GET['tag']) || $p == 'tag' )
    {
        $file = "pmail.register.recom.tpl";
        $tag = $_GET['tag'];
        $gSmarty->assign("tag", $tag);
        $chan_ids = DisChannelCtrl::list_channel_ids($tag);
        $channels = DisChannelCtrl::parse_channels($chan_ids);
        $gSmarty->assign("channels", $channels);
    }
    else if( isset($_GET['rank']) || $p == 'rank' )
    {
        $file = "pmail.register.rank.tpl";
        $cu = new DisChanUserCtrl($user_id);
        $_ids = $cu->list_subscribed_ids();
        $channels = $user->list_channels($_ids);
        $gSmarty->assign("channels", $channels);
    }
    else if( isset($_GET['info']) || $p == 'info' )
    {
        $file = "pmail.register.info.tpl";
    }
    else if( isset($_GET['publish']) || $p == 'publish' )
    {
        $file = "pmail.register.publish.tpl";
        $mn = new DisMoneyLogCtrl($user_id);
        $charge = $mn->hasCharge();
        $gSmarty->assign("charged", $charge);
        $logs = $mn->list_logs();
        $gSmarty->assign("money_list", $logs);
    }
    else if( $veri )
    {
        $file = "pmail.register.veri.tpl";
        $_key = DisUserCtrl::verify_code($user_id);
        if( $veri != $_key )
            throw new DisException('验证码错误！');
        $user = DisUserCtrl::user($user_id);
        $user->increase('rank');
    }
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("err", $err);
$gSmarty->assign("title", "新用户注册");
$gSmarty->display("pages/$file");
?>
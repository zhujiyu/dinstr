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
//    $matches = null;
//    $url = null;
//    $val = preg_match('/regi\/([0-9a-z]{12})$/i', $url, $matches);
//    if( $val )
//    {
//        header('Location: ../regi?invi='.$matches[1]); exit;
//    }
//
//    $p = $_GET['p'] ? $_GET['p'] : $_POST['p'];
//    $intr = $_GET['intr'] ? $_GET['intr'] : $_POST['intr'];
//    $invi = $_GET['invi'] ? $_GET['invi'] : $_POST['invi'];
//    $veri = $_GET['veri'] ? $_GET['veri'] : $_POST['veri'];
    
    $gSmarty = init_smarty();
    $p = $_REQUEST['p'];
    $intr = $_REQUEST['intr'];
    $invi = $_REQUEST['invi'];
    $veri = $_REQUEST['veri'];

    if( isset($_SESSION['userId']) && $_SESSION['userId'] > 0 
            && DisUserLoginCtrl::check_inline($_SESSION['userId']) )
    {
        $user_id = $_SESSION['userId'];
        $user = DisUserCtrl::user($user_id);
        $gSmarty->assign("user", $user->info());
        DisUserLoginCtrl::set_inline($user_id);
    }
    else
        $user_id = $_REQUEST['id'];
    $file = "register.page.tpl";

    if( $intr )
    {
        $intr_user = DisUserCtrl::get_data($intr);
        $gSmarty->assign("introUser", $intr_user);
    }

    if( $invi )
    {
        $invite = DisInviteCtrl::check($invi);
        $intr = $invite['user_id'];
        $email = $invite['email'];
        $gSmarty->assign("invite", $invite);
        $gSmarty->assign("email", $email);
    }

    if( isset($_POST['regi']) || $p == 'regi' )
    {
        $email = $_REQUEST['email'];
        $user = DisUserCtrl::register(md5($_POST['pword']), $email, $_REQUEST['uname']);

        // 注册完成后 继续激活操作！
        $user_id = $user->ID;
        DisUserLoginCtrl::set_inline($user_id);
        $_SESSION['userId'] = $user_id;
        
        if( $intr )
            $user->follow((int)$intr);

        $user_data = $user->info();
        $user_data['avatar'] = array('ID'=>0, 'small'=>'css/logo/avatar_s.jpg', 'big'=>'css/logo/avatar_b.jpg');
        $gSmarty->assign("user", $user_data);

        if( $invi )
        {
            DisInviteCtrl::update($invi, $user_id);
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
        $file = "register.recom.tpl";
        $tag = $_GET['tag'];
        $gSmarty->assign("tag", $tag);
        $chan_ids = DisChannelCtrl::list_chan_ids($tag);
        $channels = DisChannelCtrl::parse_chans($chan_ids);
        $gSmarty->assign("channels", $channels);
    }
    else if( isset($_GET['rank']) || $p == 'rank' )
    {
        $file = "register.rank.tpl";
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

//echo $file;
$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("err", $err);
$gSmarty->assign("title", "新用户注册");
$gSmarty->display("pages/$file");
?>
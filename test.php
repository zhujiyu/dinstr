<?php
/**
 * @package: DIS.PAGE
 * @file   : guest.php
 * 个人首页
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once 'common.inc.php';

$val = array("石中玉", "石破天");
foreach( $val as $key=>$name )
{
    echo "$key=>$name<br>";
}
foreach( $val as $name )
{
    echo "=>$name<br>";
}
exit();

//        $user_id = 1000000;
        $user_id = $_SESSION['userId'];
        DisObject::print_array($user_id);
        $user = DisUserCtrl::user($user_id);
        DisUserCtrl::set_inline($user_id);
        DisObject::print_array($user);
//        $gSmarty->assign("user", $user->info());
//
//        if( $user_id > 0 )
//        {
//            $status = $user->get_channel_status($channel_id);
//            $gSmarty->assign("status", $status);
//            $role = $status[role];
//        }
//
//        $_ids = DisChannelCtrl::list_channel_ids($_GET['tag']);
//
//        if( $user_id > 0 )
//            $channels = $user->list_channels($_ids);
//        else
//            $channels = DisChannelCtrl::parse_channels($_ids);

exit();
?>
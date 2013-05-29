<?php
/**
 * @package: DIS.INIT
 * @file   : chan.init.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

try
{
    echo "delete channel data...\n\n";
    DisDBTable::query("delete from channels");
    DisDBTable::query("delete from chan_tags");
    DisDBTable::query("delete from chan_users");
    DisDBTable::query("delete from chan_applicants");

    echo "insert new channels.\n\n";
    $avatar = new DisPhotoCtrl();
    $_sml = $_big = "css/logo/";

    $user = new DisUserCtrl('zhuhz82@126.com');
    $img1 = "bulletin_board1.png";
    $pid1 = $avatar->insert($_big.$img1, $_sml.$img1, $user->ID);
    DisDBTable::query("insert into channels (ID, name, `type`, ) values() ");
    $chan1 = DisChannelCtrl::create_channel($user->ID, "第一块板", "info",
            "自动生成的第一块测试海报板", $pid1);

    $user->init('chforlove@126.com');
    $chan1->add_subscriber($user->ID);

    $user->init('zhujiyu.tez@qq.com');
    $chan1->add_member($user->ID);

    $img2 = "bulletin_board2.png";
    $pid2 = $avatar->insert($_big.$img2, $_sml.$img2, $user->ID);
    $chan2 = DisChannelCtrl::create_channel($user->ID, "第二块板", "info",
            "自动生成的第二块海报板", $pid2);

    $user->init('chforlove@126.com');
    $chan2->add_member($user->ID);

    $user->init('zhuhz82@126.com');
    $chan2->add_subscriber($user->ID);
}
catch (DisException $ex)
{
    $ex->trace_stack();
}
?>

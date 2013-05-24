<?php
/**
 * @package: DIS.INIT
 * @file   : DisChanTest.class.php
 * Description of DisChanTest
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
    echo "delete data...\n";
    DisDBTable::query("delete from users");
    DisDBTable::query("delete from user_params");
    DisDBTable::query("delete from user_supers");
    DisDBTable::query("delete from user_logins");

    DisDBTable::query("delete from user_relations");
    DisDBTable::query("delete from user_denies");
    DisDBTable::query("delete from user_invites");
    DisDBTable::query("delete from user_feedbacks");
    DisDBTable::query("delete from photos");

    echo "insert new user data.\n";
    DisUserCtrl::register(md5('sab123'), 'zhuhz82@126.com', '朱继玉', '海报板一号用户', 'male');
    DisUserCtrl::register(md5('863sab'), 'zhujiyu.tez@qq.com', '海报板管理员', '海报板二号用户', 'female');
    DisUserCtrl::register(md5('121981'), 'chforlove@126.com', '测试用户', '测试用户', 'male');

    echo "update user avatar.\n";
    $avatar = new DisPhotoCtrl();
    $_big = "attach/avatar/xw500/";
    $_sml = "attach/avatar/nh100/";

    $user = new DisUserCtrl('zhuhz82@126.com');
    $img1 = "zhujiyu.touxiang.png";
    $pid1 = $avatar->insert($_big.$img1, $_sml.$img1, $user->ID);
    $user->update(array('avatar'=>$pid1));

    $user->init('zhujiyu.tez@qq.com');
    $img2 = "zhujiyu.touxiang.png";
    $pid2 = $avatar->insert($_big.$img2, $_sml.$img2, $user->ID);
    $user->update(array('avatar'=>$pid2));
}
catch (DisException $ex)
{
    $ex->trace_stack();
}
?>

<?php
/**
 * @package: DIS.DATA
 * @file   : DisNoticeData.class.php
 * @abstract  : 系统通知模块
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisNoticeData extends DisDBTable
{
    var $user_id;

    function  __construct($user_id)
    {
        $this->table = "notices";
        $this->user_id = $user_id;
    }

    protected function add_notice($type, $data_id, $message = '')
    {
        $str1 = "insert into new_notices (user_id, `type`, data_id, message)
            values ($this->user_id, '$type', $data_id, '$message')";
        $r1 = parent::query($str1);
        if( $r1 != 1 )
            throw new DisDBException('插入通知失败！');
        $id = parent::last_insert_Id();
        $str2 = "insert into notices (ID, user_id, `type`, data_id, message)
            values ($id, $this->user_id, '$type', $data_id, '$message')";
        $r2 = parent::query($str2);
        if( $r2 != 1 )
            throw new DisDBException('插入通知失败！');
        return $id;
    }

    static function load($id)
    {
        $str = "select * from notices where ID = $id";
        return parent::load_line_data($str);
    }

    function exist($type, $data_id)
    {
        $str = "from new_notices where user_id = $this->user_id and `type` = '$type' and data_id = $data_id";
        return parent::count($str) > 0;
    }

    function clear_notices()
    {
        $str = "delete from new_notices where user_id = $this->user_id ";
        return parent::query($str);
    }

    function remove_notice($notice_id)
    {
        $str = "delete from new_notices where ID = $notice_id ";
        return parent::query($str);
    }

    function drop_backup_notice($notice_id)
    {
        $str = "delete from notices where ID = $notice_id ";
        return parent::query($str);
    }

    function list_new_notices()
    {
        $str = "select ID, user_id, `type`, data_id, message, create_time
            from new_notices
            where user_id = $this->user_id";
        return parent::load_datas($str);
    }

    function list_approve_notices()
    {
        $str = "select n.ID, t.user_id, n.`type`, data_id, t.theme_id, message, n.create_time
            from new_notices as n left join theme_approvals as t on t.ID = n.data_id
            where n.user_id = $this->user_id and n.`type` = 'approve'";
        return parent::load_datas($str);
    }

    function list_mail_notices()
    {
        $str = "select n.ID, m.user_id, n.`type`, data_id, m.theme_id, message, n.create_time
            from new_notices as n left join mails as m on m.ID = n.data_id
            where n.user_id = $this->user_id and n.`type` = 'mail'";
        return parent::load_datas($str);
    }

    function list_reply_notices()
    {
        $str = "select n.ID, m.user_id, n.`type`, data_id, m.theme_id, message, n.create_time
            from new_notices as n left join mails as m on m.ID = n.data_id
            where n.user_id = $this->user_id and n.`type` = 'reply'";
        return parent::load_datas($str);
    }

    function list_atme_notices()
    {
        $str = "select n.ID, m.user_id, n.`type`, data_id, m.theme_id, message, n.create_time
            from new_notices as n left join mails as m on m.ID = n.data_id
            where n.user_id = $this->user_id and n.`type` = 'atme'";
        return parent::load_datas($str);
    }

    function list_fan_notices()
    {
        $str = "select n.ID, r.from_user as user_id, n.`type`, data_id, message, n.create_time
            from new_notices as n left join user_relations as r on r.ID = n.data_id
            where n.user_id = $this->user_id and n.`type` = 'fan'";
        return parent::load_datas($str);
    }

    function remove_fan_notices()
    {
        $str = "delete from new_notices where user_id = $this->user_id and `type` = 'fan'";
        return parent::query($str);
    }

    function list_apply_notices()
    {
        $str = "select n.ID, a.user_id, n.`type`, data_id, a.status, a.channel_id, message, n.create_time
            from new_notices as n left join channel_applicants as a on a.ID = n.data_id
            where n.user_id = $this->user_id and n.`type` = 'apply'";
        return parent::load_datas($str);
    }

    function remove_apply_notices()
    {
        $str = "delete from new_notices
            where user_id = $this->user_id and `type` = 'apply'";
        return parent::query($str);
    }

    function list_all_notices($page = 0, $count = 40)
    {
        $str = "select * from notices order by id desc limit ".$page*$count.", $count";
        return parent::load_datas($str);
    }
}
?>
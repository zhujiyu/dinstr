<?php
/**
 * @package: PMAIL.DATA
 * @file   : pmDataMailFlow
 *
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.11
 */
if( !defined('IN_PMAIL') )
    exit('Access Denied!');

class pmDataMailCollect extends DisObject
{
    var $user_id;

    function insert($mail_id)
    {
        $str = "insert into mail_collects (mail_id, user_id) values($mail_id, $this->user_id)";
        $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new DisDBException("收藏失败");
        return DisDBTable::last_insert_Id();
    }

    function delete($mail_id)
    {
        $str = "delete from mail_collects where user_id = $this->user_id and mail_id = $mail_id";
        return DisDBTable::query($str);
    }

    function list_mails($page = 0, $count = 40)
    {
        $str = "select * from mail_collects where user_id = $this->user_id
                    order by collect_time desc limit ".$page*$count.", $count";
        return DisDBTable::load_datas($str);
    }
}

class pmDataMailKeepTag extends DisObject
{
    static function insert($keep_id, $tag_id)
    {
        $str = "insert into mail_keep_tags (keep_id, tag_id) values ($keep_id, $tag_id)";
        return DisDBTable::query($str);
    }

    static function list_mail_ids($tag_id)
    {
        $str = "select mail_id
            from mail_keep_tags as t left join mail_keeps as k on k.ID = t.keep_id
            where t.tag_id = $tag_id";
        DisDBTable::load_datas($str);
    }

    static function delete($keep_id)
    {
        $str = "delete from mail_keep_tags where keep_id = $keep_id";
        return DisDBTable::query($str);
    }

    static function list_tags($keep_id)
    {
        $str = "select t.ID, tag_id, tag
            from mail_keep_tags as t left join keep_tags as k on k.ID = t.tag_id
            where t.keep_id = $keep_id";
        return DisDBTable::load_datas($str);
    }
}
?>
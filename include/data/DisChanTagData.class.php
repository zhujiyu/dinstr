<?php
/**
 * @package: DIS.DATA
 * @file  : pmDataChannel.php
 * @abstract  : 频道数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisChanTagData extends DisObject
{
    static function load($channel_id)
    {
        $str = "select ID, channel_id, tag from chan_tags where channel_id = $channel_id";
	    return DisDBTable::load_datas($str);
    }

    static function insert($channel_id, $tag)
    {
	    $str = "insert into chan_tags (channel_id, tag) values ($channel_id, '$tag')";
	    $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new DisDBException('添加标签失败！');
        return DisDBTable::last_insert_Id();
    }

    static function delete($tag_id)
    {
        return DisDBTable::query("delete from chan_tags where ID = $tag_id");
    }

    static function list_channel_ids($tag, $page = 0, $count = 40)
    {
        $str = "select ID, channel_id, tag from chan_tags where tag = '$tag'
            order by ID desc limit " . $page * $count . ", $count";
	    return DisDBTable::load_datas($str);
    }

    static function list_channels($tag, $page = 0, $count = 20,
            $slt = "c.ID, name, logo, `type`, description, announce, creater,
            mail_num, member_num, applicant_num, subscriber_num, create_time")
    {
        if( !$tag || $tag == '' )
            return DisChannelData::list_channels($page, $count);
        $str = " select $slt
            from chan_tags as t left join channels as c on c.ID = t.channel_id
            where tag = '$tag' group by t.channel_id
            order by t.ID desc limit ".$page * $count.", $count";
        return DisDBTable::load_datas($str);
    }
}
?>
<?php
/**
 * @package: DIS.DATA
 * @file   : DisInviteData.class.php
 * @abstract  : 私信模块
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisMessageFormData extends DisObject
{
    static function list_messages($relation_id, $page = 0, $count = 20)
    {
        $str = "select f.ID, relation_id, message_id, `read`, message, send_id, reciever_id,
                unix_timestamp(create_time) as create_time
            from message_forms as f left join messages as m on m.ID = f.message_id
            where relation_id = $relation_id order by message_id desc
            limit ".$page*$count.", $count";
        return DisDBTable::load_datas($str);
    }

    static function load($id)
    {
        $str = "select f.ID, relation_id, message_id, `read`, message, send_id, reciever_id,
                unix_timestamp(create_time) as create_time
            from message_forms as f left join messages as m on m.ID = f.message_id
            where f.ID = $id";
        return DisDBTable::load_line_data($str);
    }

    static function read($relation_id)
    {
        $str = "update message_forms as f left join message_users as u on u.ID = f.relation_id
            set `read` = 1, new_message = new_message - 1
            where f.relation_id = $relation_id and f.`read` = 0";
        return DisDBTable::query($str);
    }

    static function insert($relation_id, $message_id, $read = 0)
    {
        $str = "insert into message_forms (relation_id, message_id, `read`)
            values ($relation_id, $message_id, $read)";
        $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new DisDBException('插入私信失败！');
        return DisDBTable::last_insert_Id();
    }

    static function delete($relation_id, $message_id)
    {
        $str = "delete from message_forms where relation_id = $relation_id and message_id = $message_id";
        $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new DisException('删除失败！');
        return $r;
    }

    static function remove_messages($relation_id)
    {
        $str = "delete from message_forms where relation_id = $relation_id";
        return DisDBTable::query($str);
    }
}
?>
<?php
/**
 * @package: PMAIL.DATA
 * @file   : pmDataMessage.php
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

class DisMessageData extends DisObject
{
    static function insert($send_id, $reciever_id, $message)
    {
        if( !$send_id || !$reciever_id || !$message )
            throw new DisParamException('参数格式不正确！');
        $str = "insert into messages (send_id, reciever_id, message)
            values ($send_id, $reciever_id, '$message')";

        $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new DisDBException('插入私信失败！');
        return DisDBTable::last_insert_Id();
    }

    static function list_messages($user_id, $friend_id)
    {
        if( !$user_id || !$friend_id )
            throw new DisParamException('参数格式不正确！');

	$str = "select ID, send_id, reciever_id, message, create_time
	        from messages
	        where (send_id = $user_id and reciever_id = $friend_id)
                or (send_id = $friend_id and reciever_id = $user_id)
	        order by ID desc limit ".$page*$size.", $size ";
        $messages = DisDBTable::load_datas($str);

        $count = count($messages);
        for( $i = 0; $i < $count; $i ++ )
        {
            $messages[$i][message] = strip_tags($messages[$i][message]);
        }
	return $messages;
    }
}

class DisMessageUserData extends DisObject
{
    static function list_messages($user_id, $page = 0, $count = 20)
    {
        $str = "select u.ID, user_id, friend_id, message_num, new_message,
                m.ID as message_id, send_id, reciever_id, message,
                unix_timestamp(create_time) as create_time, f.`read`, f.ID as form_id
            from message_users as u left join messages as m on m.ID = u.last_message
                left join message_forms as f on f.relation_id = u.ID and f.message_id = u.last_message
            where user_id = $user_id and message_num > 0
            order by last_message desc limit ".$page*$count.", $count";
        return DisDBTable::load_datas($str);
    }

    static function read($relation_id)
    {
        $str = "update message_users as u left join message_forms as f on f.relation_id = u.ID and f.message_id = u.last_message
            set `read` = 1, new_message = new_message - 1 where u.ID = $relation_id";
        return DisDBTable::query($str);
    }

    static function read_first_messages($user_id)
    {
        $str = "update message_users as u left join message_forms as f on f.relation_id = u.ID and f.message_id = u.last_message
            set `read` = 1, new_message = new_message - 1 where user_id = $user_id ";
        return DisDBTable::query($str);
    }

    static function load($relation_id)
    {
        $str = "select u.ID, user_id, friend_id, message_num, new_message,
                m.ID as message_id, send_id, reciever_id, message,
                unix_timestamp(create_time) as create_time, f.`read`, f.ID as form_id
            from message_users as u left join messages as m on m.ID = u.last_message
                left join message_forms as f on f.relation_id = u.ID and f.message_id = u.last_message
            where u.ID = $relation_id and message_num > 0 order by last_message desc";
        return DisDBTable::load_line_data($str);
    }

    static function update($relation_id, $last_message, $new = 0)
    {
        $str = "update message_users
            set last_message = $last_message, message_num = message_num + 1";
        if( $new == 1 )
            $str .= ", new_message = new_message + 1 ";
        $str .= " where ID = $relation_id";
        return DisDBTable::query($str);
    }

    static function reduce($relation_id, $param, $step = 1)
    {
        $str = "update message_users set `$param` = `$param` - $step where ID = $relation_id";
        return DisDBTable::query($str);
    }

    static function delete($relation_id)
    {
        $str = "delete from message_users where ID = $relation_id";
        $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new DisException('删除失败');
        return $r;
    }

    static function insert($user_id, $friend_id)
    {
        $str = "insert into message_users (user_id, friend_id)
            values ($user_id, $friend_id)";
        $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new DisDBException('插入私信失败！');
        return DisDBTable::last_insert_Id();
    }

    static function get_id($user_id, $friend_id)
    {
        $str = "select ID from message_users
            where user_id = $user_id and friend_id = $friend_id";
        $data = DisDBTable::load_line_data($str);
        if( $data == null )
            return 0;
        return $data["ID"];
    }

    static function get_friend($relation_id)
    {
        $str = "select friend_id from message_users where ID = $relation_id";
        $data = DisDBTable::load_line_data($str);
        if( $data )
            return $data['friend_id'];
        return 0;
    }
}

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
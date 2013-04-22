<?php
/**
 * @package: DIS.CTRL
 * @file   : DisMessageCtrl.class.php
 * @abstract  :
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisMessageCtrl extends DisMessageData
{
    /**
     * 读取用户和其他用户私信的统计清单
     * @param integer $user_id 用户ID
     * @return array 私信的统计清单，其中的read为0的，该汗珠执行之后即为1了，new_message的数字读取之后即减1
     */
    static function list_messages($user_id)
    {
        $messages = DisMessageUserData::list_messages($user_id);
        $len = count($messages);
        for( $i = 0, $count = 0; $i < $len; $i ++ )
        {
            $messages[$i]['friend'] = DisUserCtrl::get_data($messages[$i]['friend_id']);
            if( $messages[$i]['read'] == 0 )
            {
                //pmDataMessageUser::read($messages[$i]['ID']);
                DisMessageFormData::read($messages[$i]['ID']);
                $count ++;
            }
        }

        //$count = pmDataMessageUser::read_first_messages($user_id);
        if( $count > 0 )
        {
            $param = new DisUserParamCtrl($user_id);
            $param->reduce('msg_notice', $count);
//            $user = pmCtrlUser::user($user_id);
//            $user->reduce('msg_notice', $count);
        }
        return $messages;
    }

    static function list_user_message($user_id, $friend_id)
    {
        $relation_id = DisMessageCtrl::get_relation_id($user_id, $friend_id);
        $messages = DisMessageFormData::list_messages($relation_id);
        $count = DisMessageFormData::read($relation_id);

        if( $count > 0 )
        {
            $user = DisUserCtrl::user($user_id);
            $user->reduce('msg_notice', $count);
            DisMessageUserData::reduce($relation_id, 'new_message', $count);
        }
        return $messages;
    }

    static function send($user_id, $friend_id, $message, $send_id = 0)
    {
        $message_id = parent::insert($user_id, $friend_id, $message);
        if( $send_id == 0 )
            $send_id = DisMessageUserData::get_id($user_id, $friend_id);

        if( $send_id == 0 )
            $send_id = DisMessageUserData::insert($user_id, $friend_id);
        DisMessageUserData::update($send_id, $message_id, 0);
        DisMessageFormData::insert($send_id, $message_id, 1);

        $recieve_id = DisMessageUserData::get_id($friend_id, $user_id);
        if( $recieve_id == 0 )
            $recieve_id = DisMessageUserData::insert($friend_id, $user_id);
        DisMessageUserData::update($recieve_id, $message_id, 1);
        DisMessageFormData::insert($recieve_id, $message_id, 0);

        $param = new DisUserParamCtrl();
        $param->ID = $friend_id;
        $param->increase('msg_notice');
        return DisMessageUserData::load($send_id);
    }

    static function get_relation_id($user_id, $friend_id)
    {
        return DisMessageUserData::get_id($user_id, $friend_id);
    }

    static function get_friend($relation_id)
    {
        return DisMessageUserData::get_friend($relation_id);
    }

    static function delete($message_id, $relation_id)
    {
        DisMessageFormData::delete($relation_id, $message_id);
        DisMessageUserData::reduce($relation_id, 'message_num', 1);
    }

    static function remove_messages($relation_id)
    {
        DisMessageUserData::delete($relation_id);
        return DisMessageFormData::remove_messages($relation_id);
    }
}
?>
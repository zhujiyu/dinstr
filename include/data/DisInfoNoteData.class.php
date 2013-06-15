<?php
/**
 * @package: DIS.DATA
 * @file  : DisInfoNoteData.class.php
 * @abstract  : 信息结点
 *
 * 各个函数的参数检查不严格，不做数据完整性一致性检查，甚至数据格式的检查也不完整
 * 上层调用的时候，自己保证数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisInfoNoteData extends DisDBTable
{
    // 构造函数
    function __construct($note_id = 0)
    {
        $this->table = "info_notes";
        parent::__construct($note_id);
    }

    // context, depth, channels, publish_num,
    function init($mail_id, $slt = "ID, user_id, content, head_id, parent_id,
            photo_num, good_num, reply_num, create_time")
    {
        return parent::init($mail_id, $slt);
    }

    protected function _strip_tags($detail)
    {
        $detail['content'] = strip_tags($detail['content']);
    }

    protected function _check_param($name, $value)
    {
        switch($name)
        {
            case 'user_id' :
                if( !uid_check($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'status' :
            case 'head_id' :
            case 'parent_id':
            case 'good_num' :
            case 'photo_num':
            case 'reply_num':
                if( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'content':
                if( !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'create_time':
                if( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            default :
                return err(DIS_ERR_PARAM);
        }
        return err(DIS_SUCCEEDED);
    }

    protected function _check_num_param($param)
    {
        return in_array($param, array('publish_num', 'reply_num'));
    }

    protected function insert($user_id, $content, $head_id = 0, $parent_id = 0,
            $photo_num = 0, $good_num = 0, $video = '')//, $depth = 0, $context = "")
    {
        if( !$user_id || !$content )
            throw new DisParamException('必须传入有效的参数。');
        return parent::insert(array('user_id'=>$user_id, 'content'=>$content,
            'head_id'=>$head_id, 'parent_id'=>$parent_id,
            'photo_num'=>$photo_num, 'good_num'=>$good_num, 'video'=>$video,
            'create_time'=>time()));
    }

    function delete()
    {
        $str = "update $this->table set status = 1 where ID = $this->ID";
        return parent::check_query($str, 1);
    }

    static function load_note_replies($parent_id, $slt = "ID, user_id, content, head_id, parent_id,
            photo_num, good_num, reply_num, create_time")
    {
        if( !$parent_id )
            throw new DisParamException('参数不合法！');
        $str = "select $slt from info_notes where parent_id = $parent_id order by ID desc";
        return parent::load_datas($str);
    }

    static function load_head_notes($head_id, $slt = "ID, user_id, content, head_id, parent_id,
            photo_num, good_num, reply_num, create_time")
    {
        if( !$head_id )
            throw new DisParamException('参数不合法！');
        $str = "select $slt from info_notes where head_id = $head_id order by ID desc";
        return parent::load_datas($str);
    }

    static function last_head_note($head_id)
    {
        if( !$head_id )
            throw new DisParamException('参数不合法！');
        $str = "select *
            from info_notes where head_id = $head_id
            order by ID desc limit 1";
        return parent::load_line_data($str);
    }

    static function last_user_info($user_id)
    {
        if( !$user_id )
            throw new DisParamException('参数不合法！');
        $str = "select ID, user_id, content, head_id, parent_id, photo_num, good_num, reply_num, create_time
            from info_notes where user_id = $user_id order by ID desc limit 1";
        return parent::load_line_data($str);
    }

//    static function list_user_infos($user_id, $max_id = 0, $count = 20)
//    {
//        if( !$user_id )
//            throw new DisParamException('参数不合法！');
//        $whr = $max_id > 0 ? "and ID < $max_id" : "";
//        $str = "select ID, user_id, content, head_id, parent_id, photo_num, good_num, reply_num, create_time
//            from info_notes where user_id = $user_id and status > 0 and parent_id = 0 $whr
//            order by ID desc limit $count";
//        return parent::load_datas($str);
//    }
//
//        if( $max_id > 0 )
//            $whr = "and ID < $max_id";
//        else
//            $whr = "";
//
//    static function preg_digit_ids($data)
//    {
//        if( $data == null || $data == "" )
//            return null;
//        $item_ids = array();
//        $matches = null;
//
//        if( preg_match_all("/(\d+)#/", $data, $matches) )
//        {
//            $count = count($matches[1]);
//            for( $i = 0; $i < 13 && $i < $count; $i ++ )
//                $item_ids[$i] = $matches[1][$i];
//        }
//        return $item_ids;
//    }
//
//    static function preg_channel_list($channels)
//    {
//        if( !$channels )
//            return array();
//        $list = array();
//        $matches = null;
//
//        if( preg_match_all("/(\d+)\|(\d+)#/", $channels, $matches) )
//        {
//            $count = count($matches[0]);
//            for( $i = 0; $i < 10 && $i < $count; $i ++ )
//            {
//                $list[$i]['channel_id'] = $matches[1][$i];
//                $list[$i]['weight'] = $matches[2][$i];
//            }
//        }
//
//        return $list;
//    }
//
//    /**
//     * 记录每条信息发送到了哪些channel
//     * @param integer $channel_id 频道ID
//     * @param array $channels
//     * @param integer $published 1表示在此channel公开发表，0代表只是内部可见
//     * @return string
//     */
//    function add_channel_id($channel_id, $channels, $published = 1)
//    {
//        $list = self::preg_channel_list($channels);
//        $channels = "";
//        $count = count($list);
//
//        for( $i = 0; $i < $count && $i < 10; $i ++ )
//        {
//            if( $list[$i]['channel_id'] == $channel_id )
//            {
//                if( $list[$i]['published'] == 1 )
//                    $published = 1;
//                continue;
//            }
//            $channels .= $list[$i]['channel_id'].'|'.$list[$i]['published']."#";
//        }
//
//        $channels = $channel_id.'|'.$published.'#'.$channels;
//        return $channels;
//    }
//
//    static function load_theme_replies($head_id, $slt = "ID, user_id, content, head_id, parent_id, depth, context,
//            photo_num, good_num, channels, reply_num, publish_num, create_time")
//    {
//        if( !$head_id )
//            throw new DisParamException('参数不合法！');
//        $str = "select $slt from info_notes where head_id = $head_id and parent_id > 0 order by ID desc";
//        return parent::load_datas($str);
//    }

}
?>
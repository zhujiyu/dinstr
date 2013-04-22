<?php
/**
 * @package: DIS.DATA
 * @file  : pmDataMail.php
 *
 * 各个函数的参数检查不严格，不做数据完整性一致性检查，甚至数据格式的检查也不完整
 * 上层调用的时候，自己保证数据
 *
 * @abstract  : 邮件数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisNoteData extends DisDBTable
{
    // 构造函数
    function __construct($mail_id = 0)
    {
        $this->table = "mails";
        parent::__construct($mail_id);
    }

    function init($mail_id, $slt = "ID, user_id, content, theme_id, parent, context, depth,
            photo_num, good_num, channels, reply_num, publish_num, create_time")
    {
        return parent::init($mail_id, $slt);
    }

    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'user_id' :
                if( !uid_check($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
//            case 'extend' :
            case 'status' :
            case 'theme_id' :
            case 'parent' :
            case 'depth' :
            case 'good_num' :
            case 'photo_num' :
            case 'reply_num' :
            case 'publish_num' :
                if( !is_integer($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'content' :
            case 'context' :
            case 'channels' :
                if( !is_string($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'create_time' :
                if( !is_integer($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            default :
                return err(PMAIL_ERR_PARAM);
        }
        return err(PMAIL_SUCCEEDED);
    }

    protected function _check_num_param($param)
    {
        return in_array($param, array('publish_num', 'reply_num'));
    }

    protected function insert($user_id, $content, $theme_id = 0, $photo_num = 0, $good_num = 0, $video = '',
            $parent = 0, $depth = 0, $context = "")
    {
        if( !$user_id || !$content )
            throw new DisParamException('必须传入有效的参数。');
        return parent::insert(array('user_id'=>$user_id, 'content'=>$content,
            'photo_num'=>$photo_num, 'good_num'=>$good_num, 'video'=>$video,
            'theme_id'=>$theme_id, 'parent'=>$parent, 'context'=>$context, 'depth'=>$depth,
            'create_time'=>time()));
    }

    static function preg_digit_ids($data)
    {
        if( $data == null || $data == "" )
            return null;
        $item_ids = array();

        if( preg_match_all("/(\d+)#/", $data, $matches) )
        {
            $count = count($matches[1]);
            for( $i = 0; $i < 13 && $i < $count; $i ++ )
                $item_ids[$i] = $matches[1][$i];
        }
        return $item_ids;
    }

    static function preg_channel_list($channels)
    {
        if( !$channels )
            return array();
        $list = array();

        if( preg_match_all("/(\d+)\|(\d+)#/", $channels, $matches) )
        {
            $count = count($matches[0]);
            for( $i = 0; $i < 10 && $i < $count; $i ++ )
            {
                $list[$i]['channel_id'] = $matches[1][$i];
                $list[$i]['weight'] = $matches[2][$i];
            }
        }

        return $list;
    }

    /**
     * 记录每条信息发送到了哪些channel
     * @param integer $channel_id 频道ID
     * @param array $channels
     * @param integer $published 1表示在此channel公开发表，0代表只是内部可见
     * @return string
     */
    function add_channel_id($channel_id, $channels, $published = 1)
    {
        $list = self::preg_channel_list($channels);
        $channels = "";
        $count = count($list);

        for( $i = 0; $i < $count && $i < 10; $i ++ )
        {
            if( $list[$i]['channel_id'] == $channel_id )
            {
                if( $list[$i]['published'] == 1 )
                    $published = 1;
                continue;
            }
            $channels .= $list[$i]['channel_id'].'|'.$list[$i]['published']."#";
        }

        $channels = $channel_id.'|'.$published.'#'.$channels;
        return $channels;
    }

    function delete()
    {
        $str = "update mails set status = 1 where ID = $this->ID";
        return parent::query($str) == 1;
    }

    static function load_mail_replies($parent, $slt = "ID, user_id, content, theme_id, parent, depth, context,
            photo_num, good_num, channels, reply_num, publish_num, create_time")
    {
        if( !$parent )
            throw new DisParamException('参数不合法！');
        $str = "select $slt from mails where parent = $parent order by ID desc";
        return parent::load_datas($str);
    }

    static function load_theme_replies($theme_id, $slt = "ID, user_id, content, theme_id, parent, depth, context,
            photo_num, good_num, channels, reply_num, publish_num, create_time")
    {
        if( !$theme_id )
            throw new DisParamException('参数不合法！');
        $str = "select $slt from mails where theme_id = $theme_id and parent > 0 order by ID desc";
        return parent::load_datas($str);
    }

    static function load_theme_mails($theme_id, $slt = "ID, user_id, content, theme_id, parent, depth, context,
            photo_num, good_num, channels, reply_num, publish_num, create_time")
    {
        if( !$theme_id )
            throw new DisParamException('参数不合法！');
        $str = "select $slt from mails where theme_id = $theme_id order by ID desc";
        return parent::load_datas($str);
    }

    static function last_theme_mail($theme_id)
    {
        $str = "select * from mails where theme_id = $theme_id
            order by ID desc limit 1";
        return parent::load_line_data($str);
    }

    static function last_user_mail($user_id)
    {
        $str = "select ID, user_id, content, theme_id, parent, depth, context,
            photo_num, good_num, channels, reply_num, publish_num, create_time
            from mails where user_id = $user_id order by ID desc limit 1";
        return parent::load_line_data($str);
    }

    static function list_user_mails($user_id, $max_id = 0, $count = 20)
    {
        if( !$user_id )
            throw new DisParamException('参数不合法！');

        if( $max_id > 0 )
            $whr = "and ID < $max_id";
        else
            $whr = "";

        $str = "select ID, user_id, content, theme_id, parent, depth, context,
            photo_num, good_num, channels, reply_num, publish_num, create_time
            from mails where user_id = $user_id $whr
            order by ID desc limit $count";
        return parent::load_datas($str);
    }
}

class DisNotePhotoData extends DisObject
{
    static function insert($mail_id, $photo_id, $rank = 0, $desc = '')
    {
        $str = "insert into mail_photos (mail_id, photo_id, `rank`, `desc`)
            values ($mail_id, $photo_id, $rank, '$desc')";
        return DisDBTable::query($str) == 1;
    }

    static function list_mail_photos($mail_id)
    {
        $str = "select ID, mail_id, photo_id, `rank`, `desc`
            from mail_photos where mail_id = $mail_id";
        return DisDBTable::load_datas($str);
    }

    static function get_data($id)
    {
        $str = "select ID, mail_id, photo_id, `rank`, `desc`
            from mail_photos where ID = $id";
        return DisDBTable::load_line_data($str);
    }
}

class DisNoteGoodData extends DisObject
{
    static function insert($mail_id, $good_id, $rank = 0, $desc = '')
    {
        $str = "insert into mail_goods (mail_id, good_id, `rank`, `desc`)
            values ($mail_id, $good_id, $rank, '$desc')";
        return DisDBTable::query($str) == 1;
    }

    static function list_mail_goods($mail_id)
    {
        $str = "select ID, mail_id, good_id, `rank`, `desc`
            from mail_goods where mail_id = $mail_id";
        return DisDBTable::load_datas($str);
    }

    static function get_data($id)
    {
        $str = "select ID, mail_id, good_id, `rank`, `desc`
            from mail_goods where ID = $id";
        return DisDBTable::load_line_data($str);
    }
}

class DisNoteReplyData extends DisObject
{
    static function insert($mail_id, $user_id)
    {
        $str = "insert into mail_replies (mail_id, user_id) values ($mail_id, $user_id)";
        return DisDBTable::query($str) == 1;
    }

    static function load_reply_mails($user_id, $max_id = 0, $count = 20)
    {
        $whr = $max_id > 0 ? " and mail_id < $max_id " : "";
        $str = "select mail_id from mail_replies
            where user_id = $user_id $whr
            order by mail_id desc limit $count";
//            order by mail_id desc limit " . $page * $size . ", $count";
        return DisDBTable::load_datas($str);
    }
}

class DisNoteKeywordData extends DisObject
{
    static function insert($mail_id, $keyword)
    {
        if( !$nid || !$keyword )
            throw new DisParamException("输入参数不合法！");
        $str = "insert into mail_keywords (mail_id, keyword) values ($mail_id, '$keyword')";
        return DisDBTable::query($str) == 1;
    }

    static function delete($mail_id)
    {
        if( !$mail_id )
            throw new DisParamException("输入参数不合法！");
        $str = "delete from mail_keywords where mail_id = $mail_id";
        return DisDBTable::query($str);
    }

    static function list_releases($keyword, $page = 0, $size = 20)
    {
        $str = "select k.mails_id, r.ID as release_id
            from mail_keywords as k, mails_publishs as r
            where k.keyword = '$keyword' and r.mails_id = k.mails_id
            group by k.ID order by r.ID desc limit ".$page*$size.", ".$size;
        return DisDBTable::load_datas($str);
    }

    static function list_mails($keyword, $page = 0, $size = 20)
    {
        $str = "select ID, mail_id, keyword from mail_keywords where keyword = '$keyword'
            order by mail_id desc limit ".$page*$size.", ".$size."";
        return DisDBTable::load_datas($str);
    }
}

class DisNoteFlowData extends DisDBTable
{
    // 构造函数
    function __construct($id = 0)
    {
        $this->table = 'mail_flows';
        parent::__construct($id);
    }

    function insert($user_id, $mail_id, $channel_id, $weight = 0)
    {
        if( !$mail_id || !$user_id )
            throw new DisParamException("参数不合法！");
        $str = "insert into $this->table (user_id, mail_id, channel_id, weight)
            values ($user_id, $mail_id, $channel_id, $weight)";
        if( parent::query($str) != 1 )
            throw new DisDBException("插入信息流数据失败！");
        return parent::last_insert_Id();
    }

    static function load_flow_note($flow_id)
    {
        $str = "select ID, user_id, mail_id, channel_id, weight, flow_time
            from mail_flows where ID = $flow_id";
        return parent::load_line_data($str);
    }

    static function top_channel_all_flows($channel_id, $count = 20)
    {
        $str = "select ID, user_id, mail_id, channel_id, flow_time
            from mail_flows
            where channel_id = $channel_id
            order by ID desc limit $count";
        return parent::load_datas($str);
    }

    static function list_channel_all_flows($channel_id, $max_id = 0, $count = 20)
    {
        $str = "select ID, user_id, mail_id, channel_id, flow_time
            from mail_flows
            where channel_id = $channel_id and ID < $max_id
            order by ID desc limit $count";
        return parent::load_datas($str);
    }

    static function load_value_mails($channel_id, $start, $end)
    {
        $str = "select ID, user_id, mail_id, flow_time, weight
            from mail_flows
            where channel_id = $channel_id
                and flow_time >= from_unixtime($start) and flow_time < from_unixtime($end)
            order by weight desc limit 200";
        return parent::load_datas($str);
    }
}
?>
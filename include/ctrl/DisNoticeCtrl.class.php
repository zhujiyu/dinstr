<?php
/**
 * @package: DIS.CTRL
 * @file   : DisNoticeCtrl.class.php
 * @abstract  : 系统通知
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisNoticeCtrl extends DisNoticeData
{
    function  __construct($user_id)
    {
        parent::__construct($user_id);
    }

    static function preg_notices($notices)
    {
        $matches = null;
        if( preg_match_all('/\d+/', $notices, $matches) )
            $notice_ids = $matches[0];
        else
            $notice_ids = array();
        return $notice_ids;
    }

    static function get_data($notice_id)
    {
        $notice = DisRowCache::get_notice($notice_id);
        if( !$notice )
        {
            $notice = DisNoticeData::load($notice_id);
            DisRowCache::set_notice($notice_id, $notice);
        }
        return $notice;
    }

    function clear_notices()
    {
        parent::clear_notices();
        DisUserVectorCache::set_new_notice_ids($this->user_id, array('0'=>'#E#'));
    }

    function remove_notices($notice_ids)
    {
        $_ids = array();
        $nlen = count($notice_ids);
        for( $i = 0; $i < $nlen; $i ++ )
        {
            $r = parent::remove_notice($notice_ids[$i]);
            if( $r > 0 )
                $_ids[] = $notice_ids[$i];
        }

        $len1 = count($_ids);
        if( $len1 == 0 )
            return;
        $notices = DisUserVectorCache::get_new_notice_ids($this->user_id);
        if( !$notices || $notices[0] == '#E#' )
            return;

        for( $i = 0; $i < $len1; $i ++ )
        {
            $len2 = count($notices);
            for( $j = 0; $j < $len2; $j ++ )
            {
                if( $notices[$j] == $notice_ids[$i] )
                {
                    array_splice($notices, $j, 1);
                    break;
                }
            }
        }

        DisUserVectorCache::set_new_notice_ids($this->user_id, $notices);
    }

    function get_unread_notice_ids()
    {
//        pmCacheUserVector::set_new_notice_ids($this->user_id, null);
        $notice_ids = DisUserVectorCache::get_new_notice_ids($this->user_id);
        if( !$notice_ids )
        {
            $notice_ids[0] = '#E#';
            $notices = parent::list_new_notices();
            $len = count($notices);
            for( $i = 0; $i < $len; $i ++ )
            {
                $notice_ids[$i] = $notices[$i]['ID'];
            }
            DisUserVectorCache::set_new_notice_ids($this->user_id, $notice_ids);
        }

        if( $notice_ids[0] == '#E#' )
            return array();
        return $notice_ids;
    }

    function get_incr_notice_ids($readed)
    {
        $notices = $this->get_unread_notice_ids();
        $len = count($notices);
        if( $len > $readed )
            return array_slice($notices, $readed);
        else
            return array();
    }

    function _parse($notice)
    {
        $notice['create_time'] = strtotime($notice['create_time']);
        if( $notice['type'] == 'mail' || $notice['type'] == 'reply' )
        {
            $mail = DisNoteCtrl::get_data($notice['data_id']);
            $user = DisUserCtrl::get_data($mail['user_id']);
            $theme = DisHeadCtrl::get_data($mail['theme_id']);

            $notice['theme_id'] = $mail['theme_id'];
            $notice['theme'] = $theme['content'];
            $notice['mail_user_id'] = $mail['user_id'];
            $notice['mail_username'] = $user['username'];
        }
        else if( $notice['type'] == 'approve' )
        {
            $approve = DisInfoUserCtrl::get_data($notice['data_id']);
            $user = DisUserCtrl::get_data($approve['user_id']);
            $theme = DisHeadCtrl::get_data($approve['theme_id']);

            $notice['theme_id'] = $approve['theme_id'];
            $notice['theme'] = $theme['content'];
            $notice['approve_user_id'] = $approve['user_id'];
            $notice['approve_username'] = $user['username'];
        }
        else if( $notice['type'] == 'apply' )
        {
            $apply = DisChanApplicantCtrl::get_data($notice['data_id']);
            $user = DisUserCtrl::get_data($apply['user_id']);
            $channel = DisChannelCtrl::get_data($apply['channel_id']);

            $notice['channel'] = $channel['name'];
            $notice['channel_id'] = $apply['channel_id'];
            $notice['apply_user_id'] = $apply['user_id'];
            $notice['apply_username'] = $user['username'];
            $notice['status'] = $apply['status'];
        }
        else if( $notice['type'] == 'fan' )
        {
            $user = DisUserCtrl::get_data($notice['data_id']);
            $notice['fan_user_id'] = $notice['data_id'];
            $notice['fan_username'] = $user['username'];

//            $relation = pmCtrlUserRelation::get_data($notice[data_id]);
//            $user = pmCtrlUser::get_data($relation[from_user]);
//            $notice[fan_user_id] = $relation[from_user];
//            $notice[fan_username] = $user[username];
        }
        else
        {
            throw new DisException('无效的类型');
        }

        return $notice;
    }

    function parse_notice_ids($notice_ids)
    {
        $drop_ids = $notice_list = array();
        $len = count($notice_ids);

        for( $i = 0; $i < $len; $i ++ )
        {
            try
            {
                $notice = self::get_data($notice_ids[$i]);
                $n_data = $this->_parse($notice);
            }
            catch ( DisException $ex )
            {
                $drop_ids[] = $notice_ids[$i];
//                $ex->trace_stack();
                continue;
            }
            $notice_list[] = $n_data;
        }

        if( count($drop_ids) > 0 )
            $this->remove_notices($drop_ids);
        return $notice_list;
    }

    /**添加新通知
     * @param string $type 通知的类型
     * @param Integer $data_id 产生通知的数据ID
     * @param string $message 通知信息
     * @throws DisParamException 输入数据类型有误
     */
    function add_new_notice($type, $data_id, $message = '')
    {
        if( !$this->user_id || !$data_id )
            throw new DisParamException("参数不合法！");

        if( parent::exist($type, $data_id) )
            return;
        $id = parent::add_notice($type, $data_id, $message);

        $notices = DisUserVectorCache::get_new_notice_ids($this->user_id);
        if( $notices != null && $notices[0] != '#E#' )
            array_unshift($notices, $id);
        else
            $notices[0] = $id;
        DisUserVectorCache::set_new_notice_ids($this->user_id, $notices);
    }

    function add_reply_notice($data_id, $message = '')
    {
        $this->add_new_notice('reply', $data_id, $message);
    }

    function add_approve_notice($data_id, $message = '')
    {
        $this->add_new_notice('approve', $data_id, $message);
    }

    function add_apply_notice($data_id, $message = '')
    {
        $this->add_new_notice('apply', $data_id, $message);
    }

    function add_follow_notice($data_id, $message = '')
    {
        $this->add_new_notice('follow', $data_id, $message);
    }

//    function add_mail_notice($data_id, $message = '')
//    {
//        $this->add_new_notice('mail', $data_id, $message);
//    }
}
?>
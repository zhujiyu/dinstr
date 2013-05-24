<?php
/**
 * @package: DIS.CTRL
 * @file   : DisUserCtrl.class.php
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

class DisUserCtrl extends DisUserData
{
    function  __construct($usr = null)
    {
        parent::__construct($usr);
    }

    function increase($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::increase($param, $step);
        DisUserDataCache::set_user_data($this->ID, $this->detail);
    }

    function reduce($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::reduce($param, $step);
        DisUserDataCache::set_user_data($this->ID, $this->detail);
    }

//    static function set_inline($user_id)
//    {
//        $login = new DisUserLoginCtrl($user_id);
//        $login->last_login();
//
//        if( $login->ID )
//        {
//            $in_time = time() - strtotime($login->attr('logout'));
//            if( $in_time > 300 )
//            {
//                $login->checkin();
//                $param = new DisUserParamCtrl();
//                $param->ID = $user_id;
//                $param->increase('online_times', $in_time);
//            }
//        }
//
//        DisUserDataCache::set_last_inline($user_id);
//    }
//
//    static function check_inline($user_id)
//    {
//        $ur = DisUserDataCache::get_last_inline($user_id);
//        if( $ur && time() - $ur < 360 )
//            return true;
//        return false;
//    }

    static function get_data($user_id)
    {
//        pmCacheUserData::set_user_data($user_id, null);
        $user_data = DisUserDataCache::get_user_data($user_id);
        if( !$user_data )
        {
            $user = new DisUserCtrl($user_id);
            if( !$user->ID )
                throw new DisException("用户不存在！");
            $user_data = $user->info();

            if( !isset($user_data['avatar']) || empty($user_data['avatar']) )
            {
                $user_data['avatar'] = array('ID'=>0, 'small'=>'css/logo/avatar_s.jpg',
                    'big'=>'css/logo/avatar_b.jpg');
            }
            else
            {
                $ph = new DisPhotoCtrl((int)$user_data['avatar']);
                $user_data['avatar'] = $ph->info();
            }
            DisUserDataCache::set_user_data($user_id, $user_data);
        }

        $user_data['param'] = DisUserParamCtrl::get_data($user_id);
        return $user_data;
    }

    static function get_user_view($user_id)
    {
        $user = self::get_data($user_id);
        $flow_id = self::get_last_flow_id($user_id);
        if( $flow_id > 0 )
            $user['news'] = DisStreamCtrl::get_flow_view($flow_id);
        return $user;
    }

    static function parse_users($user_ids)
    {
        $len = count($user_ids);
        for ( $i = 0; $i < $len; $i ++ )
        {
            try
            {
                $user = self::get_data($user_ids[$i]);
                $mail_id = self::get_last_note_id($user_ids[$i]);
                if( $mail_id > 0 )
                {
                    $user['mail'] = DisNoteCtrl::get_note_view($mail_id);
                    $user['mail'][content] = strip_tags($user['mail'][content]);
                }
            }
            catch (DisException $ex)
            {
//                $ex->trace_stack();
                continue;
            }
            $user_list[] = $user;
        }
        return $user_list;
    }

    function list_users($user_ids)
    {
        $user_list = self::parse_users($user_ids);
        $len = count($user_list);
        for( $i = 0; $i < $len; $i ++ )
            $user_list[$i]['followed'] = $this->check_follow($user_list[$i][ID]);
        return $user_list;
    }

    function list_channels($channel_ids)
    {
        $channels = array();
        $len = count($channel_ids);
        for( $i = 0; $i < $len; $i ++ )
        {
            try
            {
                $channel = DisChannelCtrl::get_data($channel_ids[$i]);
                $channel[member] = $this->get_channel_status($channel_ids[$i]);
            }
            catch (DisException $ex)
            {
                $ex->trace_stack();
                continue;
            }
            $channels[] = $channel;
        }
        return $channels;
    }

    function list_themes($theme_ids)
    {
        $themes = array();
        $len = count($theme_ids);
        for( $i = 0; $i < $len; $i ++ )
        {
            $theme = DisHeadCtrl::head($theme_ids[$i]);
            $data = $theme->head_view();
            $data['status'] = $theme->check_status($this->ID);
            $data['last_mail'] = $theme->last_note();
            array_push($themes, $data);
        }
        return $themes;
    }

    function list_mails($mail_ids)
    {
        $mail_list = array();
        $count = count($mail_ids);

        for( $i = 0; $i < $count; $i ++ )
        {
            $mail = DisNoteCtrl::get_note_view($mail_ids[$i]);
            $mail[content] = strip_tags($mail[content]);
            $theme = DisHeadCtrl::head($mail['theme_id']);
            $mail[theme] = $theme->info();
            $mail[theme][status] = $theme->check_status($this->ID);
            array_push($mail_list, $mail);
        }
        return $mail_list;
    }

    function list_flows($flow_ids)
    {
        $flow_list = array();
        $len = count($flow_ids);

        for( $i = 0; $i < $len; $i ++ )
        {
            try
            {
                $flow = DisStreamCtrl::get_flow_view($flow_ids[$i]);
                $theme = DisHeadCtrl::head($flow['theme_id']);
                $flow['theme']['status'] = $theme->check_status($this->ID);
            }
            catch (DisException $ex)
            {
//                $ex->trace_stack();
                continue;
            }
            array_push($flow_list, $flow);
        }
        return $flow_list;
    }

    function list_follow_user_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

//        pmCacheUserVector::set_follow_user_ids((int)$this->ID, null);
        $follow_ids = DisUserVectorCache::get_follow_user_ids((int)$this->ID);
        if( !$follow_ids )
        {
            $follow_ids[0] = "#E#";
            $list = DisUserRelationData::follows((int)$this->ID);
            $count = count($list);
            for( $i = 0; $i < $count; $i ++ )
                 $follow_ids[$i] = $list[$i]['to_user'];
            DisUserVectorCache::set_follow_user_ids((int)$this->ID, $follow_ids);
        }

        if( $follow_ids[0] == "#E#" )
            $follow_ids = array();
        return $follow_ids;
    }

    function list_fan_user_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

//        pmCacheUserVector::set_fan_user_ids((int)$this->ID, null);
        $fan_ids = DisUserVectorCache::get_fan_user_ids((int)$this->ID);
        if( !$fan_ids )
        {
            $fan_ids[0] = "#E#";
            $list = DisUserRelationData::fans((int)$this->ID);
            $count = count($list);
            for( $i = 0; $i < $count; $i ++ )
                 $fan_ids[$i] = $list[$i]['from_user'];
            DisUserVectorCache::set_fan_user_ids((int)$this->ID, $fan_ids);
        }

        if( $fan_ids[0] == "#E#" )
            $fan_ids = array();
        return $fan_ids;
    }

    static function list_super_user_ids()
    {
//        pmCacheUserVector::set_super_user_ids(null);
        $u_ids = DisUserVectorCache::get_super_user_ids();
        if( !$u_ids )
        {
            $u_ids[0] = '#E#';
            $users = parent::list_super_users();
            $len = count($users);
            for( $i = 0; $i < $len; $i ++ )
                $u_ids[$i] = $users[$i]['user_id'];
            DisUserVectorCache::set_super_user_ids($u_ids);
        }

        if( $u_ids[0] == '#E#' )
            return array();
        return $u_ids;
    }

    function check_editor($channel_id)
    {
        $super_ids = self::list_super_user_ids();
        if( in_array($this->ID, $super_ids) )
            return true;

        $cu = new DisChanUserCtrl($this->ID);
        $roles = $cu->list_channel_roles();
        return $roles[$channel_id] > 1;
    }

    function get_channel_role($channel_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        $cu = new DisChanUserCtrl($this->ID);
        return $cu->get_channel_role($channel_id);
    }

    function get_channel_status($channel_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $role = $this->get_channel_role($channel_id);
        if( $role >= 0 )
            $status = DisChanUserCtrl::get_data((int)$this->ID, (int)$channel_id);
        else
            $status = array('ID'=>0, 'channel_id'=>$channel_id, 'user_id'=>$this->ID,
                'role'=>-1, 'weight'=>0, 'rank'=>0);

        $user_ids = $this->list_super_user_ids();
        if( in_array($this->ID, $user_ids) )
            $status[role] = 3;

//        $status['role'] = $status['role'] == 'creator' ? 'editor' : $status['role'];
        return $status;
    }

    function push_follow_flow_id($flow_id)
    {
        $fids = $this->list_follow_flow_ids(0);
        array_unshift($fids, $flow_id);
        DisUserVectorCache::set_follow_flow_ids($this->ID, $fids);
    }

    protected function _list_interest_theme_ids($max_id = 0, $count = 200)
    {
        $theme_ids = array();
        $ids = DisInfoUserData::list_interest_theme_ids($this->ID, $max_id, $count);
        $len = count($ids);
        for( $i = 0; $i < $len; $i ++ )
            $theme_ids[$i] = $ids[$i]['theme_id'];
        return $theme_ids;
    }

    function list_interest_theme_ids($page = 0, $count = 200)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $start = $page * $count;
        $hope = $start + $count;
//        pmCacheUserVector::set_interest_theme_ids($this->ID, null);
        $theme_ids = DisUserVectorCache::get_interest_head_ids($this->ID);

        if( !$theme_ids )
        {
            $theme_ids = $this->_list_interest_theme_ids(0, $hope);
            if( count($theme_ids) == 0 )
                $theme_ids[0] = "#E#";
            DisUserVectorCache::set_interest_head_ids($this->ID, $theme_ids);
        }
        else if( $theme_ids[0] != "#E#" )
        {
            $len = count($theme_ids);
            if( $len < $hope )
            {
                $temp_ids = $this->_list_interest_theme_ids($theme_ids[$len - 1], $hope - $len);
                $theme_ids = array_merge($theme_ids, $temp_ids);
                DisUserVectorCache::set_interest_head_ids($this->ID, $theme_ids);
            }
        }

        return list_slice($theme_ids, $start, $count);
    }

    protected function _list_approved_theme_ids($start = 0, $count = 20)
    {
        $theme_ids = array();
        $ids = DisInfoUserData::list_approve_theme_ids($this->ID, $start, $count);
        $len = count($ids);
        for( $i = 0; $i < $len; $i ++ )
            $theme_ids[$i] = $ids[$i]['theme_id'];
        return $theme_ids;
    }

    function list_approved_head_ids($page = 0, $count = 20)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $start = $page * $count;
        $hope = $start + $count;
//        pmCacheUserVector::set_approved_theme_ids($this->ID, null);
        $head_ids = DisUserVectorCache::get_approved_head_ids($this->ID);

        if( !$head_ids )
        {
            $head_ids = $this->_list_approved_theme_ids(0, $hope);
            if( count($head_ids) == 0 )
                $head_ids[0] = "#E#";
            DisUserVectorCache::set_approved_head_ids($this->ID, $head_ids);
        }
        else if( $head_ids[0] != "#E#" )
        {
            $len = count($head_ids);
            if( $len < $hope )
            {
                $temp_ids = $this->_list_approved_theme_ids($head_ids[$len - 1], $hope - $len);
                $head_ids = array_merge($head_ids, $temp_ids);
                DisUserVectorCache::set_approved_head_ids($this->ID, $head_ids);
            }
        }

        return list_slice($head_ids, $start, $count);
    }

    private function _list_reply_mail_ids($max_id, $size = 20)
    {
        $notes = DisInfoReplyData::load_reply_mails($this->ID, $max_id, $size);
        $count = count($notes);
        $note_ids = array();
        for ( $i = 0; $i < $count; $i ++ )
            $note_ids[$i] = $notes[$i]['mail_id'];
        return $note_ids;
    }

    function list_reply_mail_ids($page, $count = 20)
    {
        $left = $page * $count;
        $hope = $left + $count;
//        pmCacheUserVector::set_reply_mail_ids($this->ID, null);
        $note_ids = DisUserVectorCache::get_reply_note_ids($this->ID);

        if( !$note_ids )
        {
            $note_ids = $this->_list_reply_mail_ids(0, $hope);
            if( count($note_ids) == 0 )
                $note_ids[0] = "#E#";
            DisUserVectorCache::set_reply_note_ids($this->ID, $note_ids);
        }
        else if( $note_ids[0] != "#E#" )
        {
            $have = count($note_ids);
            if( $have < $hope )
            {
                $temp_ids = $this->_list_reply_mail_ids($note_ids[$have - 1], $hope - $have);
                $note_ids = array_merge($note_ids, $temp_ids);
                DisUserVectorCache::set_reply_note_ids($this->ID, $note_ids);
            }
        }

        return list_slice($note_ids, $left, $count);
    }

    protected function _list_publish_note_ids($max_id = 0, $count = 20)
    {
        $notes = DisNoteCtrl::list_user_infos($this->ID, $max_id, $count);
        $count = count($notes);
        $note_ids = array();
        for( $i = 0; $i < $count; $i ++ )
            $note_ids[$i] = $notes[$i]['ID'];
        return $note_ids;
    }

    function list_publish_note_ids($page = 0, $count = 20)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $start = $page * $count;
        $hope = $start + $count;
//        pmCacheUserVector::set_publish_mail_ids($this->ID, null);
        $note_ids = DisUserVectorCache::get_publish_note_ids($this->ID);

        if( !$note_ids )
        {
            $note_ids = $this->_list_publish_note_ids(0, $hope);
            if( count($note_ids) == 0 )
                $note_ids[0] = "#E#";
            DisUserVectorCache::set_publish_note_ids($this->ID, $note_ids);
        }
        else if( $note_ids[0] != "#E#" )
        {
            $len = count($note_ids);
            if( $len < $hope )
            {
                $temp_ids = $this->_list_publish_note_ids($note_ids[$len - 1],
                        $hope - $len);
                $note_ids = array_merge($note_ids, $temp_ids);
                DisUserVectorCache::set_publish_note_ids($this->ID, $note_ids);
            }
        }

        return list_slice($note_ids, $start, $count);
    }

    static function get_last_note_id($user_id)
    {
//        pmVectorMemcached::set_last_mail_id($user_id, 0);
        $note_id = DisUserVectorCache::get_last_note_id($user_id);
        if( !$note_id )
        {
            $note_id = 0;
            $note = DisNoteCtrl::last_user_info($user_id);
            if( $note )
                $note_id = $note['ID'];
            DisUserVectorCache::set_last_note_id($user_id, $note_id);
        }
        return $note_id;
    }

    static function get_uid_by_name($username)
    {
        $user_id = DisUserDataCache::get_uid_by_name($username);
        if( !$user_id )
            $user_id = parent::get_uid_by_name($username);
        if( $user_id )
            DisUserDataCache::set_uid_by_name($username, $user_id);
        return $user_id;
    }

    static function get_uid_by_email($email)
    {
        $user_id = DisUserDataCache::get_uid_by_email($email);
        if( !$user_id )
            $user_id = parent::get_uid_by_email($email);
        if( $user_id )
            DisUserDataCache::set_uid_by_email($email, $user_id);
        return $user_id;
    }

    static function get_user_id($usr)
    {
        if( uid_check($usr) )
            $user_id = $usr;
        else if ( email_check($usr) )
            $user_id = self::get_uid_by_email($usr);
        else if ( name_check($usr) )
            $user_id = self::get_uid_by_name($usr);
        else
            throw  new DisParamException('参数格式不正确！');
        if( !$user_id )
            throw new DisException('用户不存在！');
        return $user_id;
    }

    static function user($user_id)
    {
        $user = new DisUserCtrl();
        $user->ID = $user_id;
        $user->detail = self::get_data($user_id);
        return $user;
    }

    /**
     * 新用户注册
     * @param string $username 用户名
     * @param string $password 用户密码，明码的MD5值
     * @param string $email 安全邮箱
     * @param string $sign 真实姓名
     * @param string $self_intro 自我介绍
     * @param string $live_city 居住城市
     * @param string $gender 性别
     * @return DisUserCtrl
     */
    static function register($password, $email, $username,
            $sign = '', $gender = 'none', $self_intro = '', $live_city = '')
    {
        if( self::get_uid_by_name($username) > 0 )
            throw new DisParamException('该用户名已经被占用了！');
        if( $email && self::get_uid_by_email($email) > 0 )
            throw new DisParamException('该邮箱已经注册了！');

        $salt = substr(md5(rand()), 0, 16).substr(md5(rand()), 0, 16);
        $password = md5($password.$salt);
        $user = new DisUserCtrl();
        $user->insert($username, $password, $salt, $email, $sign, $gender, $self_intro, $live_city);

        $param = new DisUserParamCtrl();
        $param->insert($user->ID);
        return $user;
    }

    /**
     * 判断是否跟踪某个人
     * @param integer $user_id 检查用户ID
     * @return boolean 关注返回true，否则返回false
     */
    function check_follow($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('传入参数不合法！');
        $follows = $this->list_follow_user_ids();
        return in_array($user_id, $follows) ? 1 : 0;
    }

    function follow($target_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$target_id )
            throw new DisParamException('传入参数不合法！');
        if( $this->check_follow($target_id) )
            throw new DisParamException('已经关注了此人，不需重复操作！');

        try
        {
            $relation_id = DisUserRelationData::insert($this->ID, $target_id);
            if( !$relation_id )
                throw new DisDBException('添加关注操作失败！');

            $param1 = new DisUserParamCtrl($this->ID);
            $param1->increase('follow_num');
            $param2 = new DisUserParamCtrl($target_id);
            $param2->increase('fans_num');

            $notice = new DisNoticeCtrl($target_id);
            $notice->add_follow_notice($this->ID);
        }
        catch (DisException $ex)
        {
            $ex->trace_stack();
        }

        DisUserVectorCache::set_follow_user_ids($this->ID, null);
        DisUserVectorCache::set_follow_flow_ids($this->ID, null);
    }

    function cancel_follow($target_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$target_id )
            throw new DisParamException('传入参数不合法！');
        if( !$this->check_follow($target_id) )
            throw new DisParamException('已经取消了对此人的关注，不需重复操作！');

        try
        {
            if( !DisUserRelationData::remove($this->ID, $target_id) )
                throw new DisDBException('添加关注操作失败！');
            $mparam = new DisUserParamCtrl($this->ID);
            $mparam->reduce('follow_num');
            $tparam = new DisUserParamCtrl($target_id);
            $tparam->reduce('fans_num');
        }
        catch (DisException $ex)
        {
            $ex->trace_stack();
        }

        DisUserVectorCache::set_follow_user_ids($this->ID, null);
        DisUserVectorCache::set_follow_flow_ids($this->ID, null);
    }

    function update($info)
    {
        if( isset($info['uname']) && $this->detail['username'] != $info['uname'] )
        {
            if( !name_check( $info['uname'] ) )
                throw new DisException('用户名格式不正确');
            $update['username'] = $info['uname'];
        }

        if( isset($info['avatar']) && $this->detail['avatar'] != $info['avatar'] )
        {
            $oldavatar = (int)$this->detail['avatar'];
            $newavatar = (int)$info['avatar'];
            $update['avatar'] = (int)$info['avatar'];
        }

        if( isset($info['self_intro']) && $this->detail['self_intro'] != $info['self_intro'] )
            $update['self_intro'] = $info['self_intro'];
        if( isset($info['sign']) && $this->detail['sign'] != $info['sign'] )
            $update['sign'] = $info['sign'];
        if( isset($info['live_city']) && $this->detail['live_city'] != $info['live_city'] )
            $update['live_city'] = $info['live_city'];
        if( isset($info['gender']) && $this->detail['gender'] != $info['gender'] )
            $update['gender'] = $info['gender'];
        if( isset($info['contact']) && $this->detail['contact'] != $info['contact'] )
            $update['contact'] = $info['contact'];

        if( isset($info['msg_setting']) && $this->detail['msg_setting'] != $info['msg_setting'] )
            $update['msg_setting'] = $info['msg_setting'];
//        if( $this->detail['atme_setting'] != $info['atme_setting'] )
//            $update['atme_setting'] = $info['atme_setting'];

        parent::update($update);
        if( isset($oldavatar) && $oldavatar > 0 )
        {
            $photo = new DisPhotoCtrl($oldavatar);
            if( $photo->ID )
                $photo->reduce('quote');
        }
        if( isset($newavatar) && $newavatar > 0 )
        {
            $photo = new DisPhotoCtrl($newavatar);
            if( $photo->ID )
                $photo->increase('quote');
        }
        DisUserDataCache::set_user_data($this->ID, null);
    }

//    function reply_notice($note_id)
//    {
//        if( !$this->ID )
//            throw new DisParamException('对象没有初始化！');
//        if( !$note_id )
//            throw new DisParamException("参数不合法");
//
//        DisInfoReplyData::insert($note_id, $this->ID);
//        $notice = new DisNoticeCtrl($this->ID);
//        $notice->add_reply_notice($note_id);
//    }

//    function reset_pword($old_pword, $new_pword)
//    {
//        if( !$this->ID )
//            throw new DisParamException('对象没有初始化！');
//        if( !$this->detail )
//            $this->detail = self::get_data($this->ID);
//
//        $r = $this->check_password($old_pword);
//        if( !$r )
//            throw new DisException('原密码错误！');
//        return $this->update_password($new_pword);
//    }

}
?>
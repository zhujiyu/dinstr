<?php
/**
 * @package: DIS.CTRL
 * @file   : DisChannelCtrl.class.php
 * @abstract  : 频道管理
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisChannelCtrl extends DisChannelData
{
    // 构造函数
    function __construct($id = null)
    {
        parent::__construct($id);
    }

    static function get_data($chan_id)
    {
//        pmCacheChanData::set_channel_data($channel_id, null);
        $chan_data = DisChanDataCache::get_chan_data($chan_id);
        if( !$chan_data )
        {
            $chan = new DisChannelCtrl((int)$chan_id);
            if( !$chan->ID )
                throw new DisException("无法读取到数据！$chan_id");
            $chan->detail['tags'] = DisChanTagData::load($chan->ID);
            $chan_data = $chan->info();

            if( !isset($chan->detail['logo']) || empty($chan->detail['logo']) )
            {
//                $channel_data['logo'] = array('ID'=>0, 'small'=>'css/logo/chanbws.png', 'big'=>'css/logo/chanbwb.png');
//                $channel_data['logo'] = array('ID'=>0, 'small'=>'css/logo/chanbgs.png', 'big'=>'css/logo/chanbgb.png');
                $chan_data['logo'] = array('ID'=>0, 'small'=>'css/logo/bulletin_board2.png', 'big'=>'css/logo/bulletin_board2.png');
            }
            else
            {
                $chan_data['logo'] = DisPhotoCtrl::get_data($chan_data['logo']);
            }

//            $channel_data['logo'] = array('ID'=>0, 'small'=>'css/logo/chanbgs.png', 'big'=>'css/logo/chanbgb.png');
            DisChanDataCache::set_chan_data($chan_id, $chan_data);
        }
        return $chan_data;
    }

    static function chan($chan_id)
    {
        $chan = new DisChannelCtrl();
        $chan->ID = $chan_id;
        $chan->detail = self::get_data($chan_id);
        return $chan;
    }

    function increase($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);

        parent::increase($param, $step);
        DisChanDataCache::set_chan_data($this->ID, null);
    }

    function reduce($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);

        parent::reduce($param, $step);
        DisChanDataCache::set_chan_data($this->ID, null);
    }

    static function list_value_flows($channel_id, $flag, $period)
    {
        $end = $flag * DisConfigAttr::$intervals['quarter'];
        $period = DisConfigAttr::$periods[$period];

//        pmVectorMemcached::set_channel_flows($channel_id, $flag, $period, null);
        $vflows = DisChanVectorCache::get_chan_flows($channel_id, $period, $flag);
        if( !$vflows )
        {
            $flows = DisStreamData::load_value_mails($channel_id, $end - $period, $end);
            $vflows['flow_ids'] = chunk_array($flows, 'ID');
            $vflows['weights']  = chunk_array($flows, 'weight');
            $vflows['info'] = array('id'=>$channel_id, 'flag'=>$flag);
            DisChanVectorCache::set_chan_flows($channel_id, $period, $flag, $vflows);
        }

        return $vflows;
    }

    static function get_member_id($channel_id, $user_id)
    {
        $join_channel_ids = self::list_join_channel_ids($user_id);
        if( !in_array($channel_id, $join_channel_ids) )
            return 0;
        $member_ids = self::list_user_member_ids($user_id);
        return $member_ids[$channel_id];
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

    function list_flow_ids($page = 0, $count = 20)
    {
        $start = $page * $count;
        $hope = $start + $count;
//        pmVectorMemcached::set_channel_flow_ids($this->ID, null);
        $flow_ids = DisChanVectorCache::get_chan_flow_ids($this->ID);

        if( !$flow_ids )
        {
            $flow_ids[0] = '#E#';
            $flows = DisStreamCtrl::top_channel_all_flows($this->ID, $hope);
            $len = count($flows);
            for( $i = 0; $i < $len; $i ++ )
                $flow_ids[$i] = $flows[$i]['ID'];
            DisChanVectorCache::set_chan_flow_ids($this->ID, $flow_ids);
        }
        else if( $flow_ids[0] != "#E#" )
        {
            $len1 = count($flow_ids);
            if( $len1 < $hope )
            {
                $flows = DisStreamCtrl::list_channel_all_flows($this->ID, $flow_ids[$len1 - 1], $hope - $len);
                $len2 = count($flows);
                for( $i = 0; $i < $len2; $i ++ )
                    array_push($flow_ids, $flows[$i]['ID']);
                DisChanVectorCache::set_chan_flow_ids($this->ID, $flow_ids);
            }
        }

        return list_slice($flow_ids, $start, $count);
    }

    function list_manager_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $manager_ids = DisChanVectorCache::get_manager_ids((int)$this->ID);
        if( !$manager_ids )
        {
            $manager_ids[0] = '#E#';
            $managers = DisChanUserData::list_managers((int)$this->ID);
            $count = count($managers);
            for( $i = 0; $i < $count; $i ++ )
                $manager_ids[$i] = $managers[$i]['user_id'];
            DisChanVectorCache::set_manager_ids((int)$this->ID, $manager_ids);
        }

        if( $manager_ids[0] == '#E#' )
            return array();
        return $manager_ids;
    }

    /**
     * 列出所有会员的用户ID
     * @param integer $channel_id 频道ID
     * @return array 会员的用户ID列表
     */
    function list_member_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        //pmVectorMemcached::set_member_ids((int)$this->ID, null);
        $member_ids = DisChanVectorCache::get_joined_user_ids((int)$this->ID);
        if( !$member_ids )
        {
            $member_ids[0] = "#E#";
            $members = DisChanUserData::list_members((int)$this->ID);
            $count = count($members);
            for( $i = 0; $i < $count; $i ++ )
                $member_ids[$i] = $members[$i]['user_id'];
            DisChanVectorCache::set_joined_user_ids((int)$this->ID, $member_ids);
        }

        if( $member_ids[0] == "#E#" )
            return array();
        return $member_ids;
    }

    function list_subscriber_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        //pmVectorMemcached::set_subscriber_ids((int)$this->ID, null);
        $subscriber_ids = DisChanVectorCache::get_subscribed_user_ids((int)$this->ID);
        if( !$subscriber_ids )
        {
            $subscriber_ids[0] = "#E#";
            $subscribers = DisChanUserData::list_subscribers((int)$this->ID);
            $count = count($subscribers);
            for( $i = 0; $i < $count; $i ++ )
                $subscriber_ids[$i] = $subscribers[$i]['user_id'];
            DisChanVectorCache::set_subscribed_user_ids((int)$this->ID, $subscriber_ids);
        }

        if( $subscriber_ids[0] == "#E#" )
            return array();
        return $subscriber_ids;
    }

    function list_applicants($page = 0, $count = 40)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        $applicants = DisChanApplicantData::list_applicants((int)$this->ID, $page, $count);
        $count = count($applicants);
        for( $i = 0; $i < $count; $i ++ )
            $applicants[$i]['user'] = DisUserCtrl::get_data($applicants[$i]['user_id']);
        return $applicants;
    }

    function add_subscriber($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('参数不合法！');

        $cu = new DisChanUserCtrl($user_id);
        $chan_ids = $cu->list_subscribed_ids();
        if( in_array($this->ID, $chan_ids) )
            return;

        $rank = $cu->get_max_rank(1);
        $cu->insert((int)$this->ID, (int)$user_id, 'subscriber', $rank + 1);
        if( !$cu->ID )
            throw new DisDBException('订阅频道操作失败！');

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->increase("subscribe_num");
        $this->increase("subscriber_num");

        DisUserVectorCache::set_subscribed_chan_ids($user_id, null);
        DisUserVectorCache::set_follow_flow_ids($user_id, null);
        DisUserVectorCache::set_chan_roles($user_id, null);
        DisChanVectorCache::set_subscribed_user_ids($this->ID, null);
        DisChanDataCache::set_chan_user_data($this->ID, $user_id, null);

//        $feed = DisFeedCtrl::read_ctrler((int)$user_id);
//        $feed->subscribe((int)$this->ID);
//        DisFeedCtrl::save_ctrler($feed);
//        return $cu;
    }

    function remove_subscriber($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('参数不合法！');

        $cu = new DisChanUserCtrl($user_id, (int)$this->ID);
        if( !$cu->ID )
            return;
        $cu->delete();
//        $chan_ids = $cu->list_subscribed_ids();
//        if( !in_array($this->ID, $chan_ids) )
//            return;
//        $cu->load((int)$this->ID);

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->reduce('subscribe_num');
        $this->reduce("subscriber_num");

        DisUserVectorCache::set_subscribed_chan_ids($user_id, null);
        DisUserVectorCache::set_follow_flow_ids($user_id, null);
        DisUserVectorCache::set_chan_roles($user_id, null);
        DisChanVectorCache::set_subscribed_user_ids($this->ID, null);
        DisChanDataCache::set_chan_user_data($this->ID, $user_id, null);

//        $feed = DisFeedCtrl::read_ctrler((int)$user_id);
//        $feed->cancel_subscribe((int)$this->ID);
//        DisFeedCtrl::save_ctrler($feed);
//        return $cu;
    }

    function add_member($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('传入的参数不正确！');

        $cu = new DisChanUserCtrl($user_id, (int)$this->ID);
        if( !$cu->ID )
        {
            $this->add_subscriber($user_id);
            $cu->load($this->ID);
        }
        $cu->change_role('member');
//        $cu->load($this->ID);
//        echo $cu->ID;
//        if( !$cu->ID )
//        {
//            echo __CLASS__.":".__LINE__;
//            $cu = $this->add_subscriber($user_id);
//        }

        $this->increase('member_num');
        $param = new DisUserParamCtrl($user_id);
        $param->increase("join_num");
//        DisObject::print_array($param->info());
//        $param->ID = $user_id;
//        DisObject::print_array($param->info());

        DisChanVectorCache::set_joined_user_ids($this->ID, null);
        DisUserVectorCache::set_joined_chan_ids($user_id, null);
        DisUserVectorCache::set_chan_roles($user_id, null);
//        return $cu;
    }

    function remove_member($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('传入的参数不正确！');

        $cu = new DisChanUserCtrl($user_id, (int)$this->ID);
//        $cu->load((int)$this->ID);
        if( !$cu->ID )
            return;
        if( $cu->attr('role') < 1 )
            return;
        $cu->change_role('subscriber');

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->reduce ("join_num");
        $this->reduce("member_num");

        DisChanVectorCache::set_joined_user_ids($this->ID, null);
        DisUserVectorCache::set_joined_chan_ids($user_id, null);
        DisUserVectorCache::set_chan_roles($user_id, null);
//        return $cu;
    }

    /**创建一个新社团
     * @param integer $creater 创建者ID
     * @param string $name 频道名字
     * @param string $type 频道类型
     * @param string $logo 头像
     * @param string $description 简短描述
     * @param string $tags 标签列表
     * @return DisChannelCtrl 对象
     */
    static function create_channel($creater_id, $name, $type = 'info',
            $desc = '对该频道进行简短描述', $logo = 0, $tags = null)
    {
        $chan = new DisChannelCtrl();
        $chan->insert((int)$creater_id, $name, $type, (int)$logo, $desc);
        if( $chan->ID == 0 )
            throw new DisDBException('插入失败！');
        $chan_id = (int)$chan->ID;

        if( $logo > 0 )
        {
            $photo = new DisPhotoCtrl($logo);
            if( $photo->ID )
                $photo->increase('quote');
        }
        if( $tags != null )
        {
            $tags = keyword_parse($tags);
            $count = count($tags);
            for( $i = 0; $i < $count; $i ++ )
                DisChanTagData::insert($chan_id, $tags[$i]);
        }

        $cu = new DisChanUserData($creater_id, $chan_id);
//        $cu->load((int)$channel->ID);
        if( !$cu->ID )
        {
            $chan->add_subscriber($creater_id);
            $cu->load($chan_id);
        }
        $cu->change_role('creator');
        $chan->increase('member_num');

        $param = new DisUserParamCtrl($creater_id);
//        $param->ID = $creater_id;
        $param->increase('create_num');
        $param->increase("join_num");
        DisUserVectorCache::set_joined_chan_ids($creater_id, null);
        DisUserVectorCache::set_chan_roles($creater_id, null);

        return $chan;
    }

    function apply($user_id, $reason)
    {
        if( !$this->ID || !$user_id )
            throw new DisParamException('参数不合法！');

        $apply = new DisChanApplicantCtrl();
        if( $apply->exist($user_id, $this->ID) )
            throw new DisParamException('申请已经发送，不用重复申请！');
        $applicant_id = $apply->insert($user_id, (int)$this->ID, $reason);

        $manager_ids = $this->list_manager_ids();
        $len = count($manager_ids);
        for( $i = 0; $i < $len; $i ++ )
        {
            $notice = new DisNoticeCtrl($manager_ids[$i]);
            $notice->add_apply_notice($applicant_id);
        }

        $this->increase("applicant_num");
        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->increase('applicant_num');
    }

    function accept_apply($apply_id)
    {
        if( !$this->ID || !$apply_id )
            throw new DisParamException('参数不合法！');

        $apply = new DisChanApplicantCtrl($apply_id);
        if( $apply->ID == 0 || $apply->attr('chan_id') != $this->ID )
            throw new DisParamException('无效的操作，该频道不存在此条申请！');
//        DisObject::print_array($apply);
//        return $apply->accept();
        if( !$apply->accept() )
            throw new DisParamException('操作失败！');
        DisChanDataCache::set_applicant_data($apply_id, null);
//        $apply = new DisChanApplicantCtrl($applicant_id);
//        DisObject::print_array($apply);

        $user_id = $apply->attr('user_id');
        $this->add_member($user_id);
        $this->reduce("applicant_num");

        $param = new DisUserParamCtrl($user_id);
        $param->reduce('applicant_num');

        $notice = new DisNoticeCtrl($user_id);
        $notice->add_apply_notice($apply_id, "你加入".$this->detail['name']."的申请已经通过！");
    }

    function refuse_apply($apply_id, $reason = "")
    {
        if( !$this->ID || !$apply_id )
            throw new DisParamException('参数不合法！');

        $apply = new DisChanApplicantCtrl($apply_id);
        if( $apply->ID == 0 || $apply->attr('chan_id') != $this->ID )
            throw new DisParamException('无效的操作，该频道不存在此条申请！');
        if( !$apply->refuse() )
            throw new DisParamException('操作失败！');
        DisChanDataCache::set_applicant_data($apply_id, null);

        $this->reduce("applicant_num");
        $user_id = $apply->attr('user_id');
        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->reduce('applicant_num');

        $notice = new DisNoticeCtrl($user_id);
        $notice->add_apply_notice($apply_id, "你加入".$this->detail['name']."的申请被拒绝！原因：".$reason);
    }

    function update($name, $desc, $logo)
    {
        if( !$this->ID )
            throw new DisParamException('参数不合法！');

        if( isset($logo) && $logo != $this->detail['logo'] && (int)$logo > 0 )
        {
            $oldlogo = (int)$this->detail['logo'];
            $newlogo = (int)$logo;
            $update['logo'] = (int)$logo;
        }

        if( isset($name) && $name != $this->detail['name'] )
            $update['name'] = $name;
        if( isset($desc) && $desc != $this->detail['description'] )
            $update['description'] = $desc;
        parent::update($update);

        if( isset($oldlogo) && $oldlogo > 0 )
        {
            $photo = new DisPhotoCtrl($oldlogo);
            if( $photo->ID )
                $photo->reduce('quote');
        }
        if( isset($oldlogo) && $newlogo > 0 )
        {
            $photo = new DisPhotoCtrl($newlogo);
            if( $photo->ID )
                $photo->increase('quote');
        }
        DisChanDataCache::set_chan_data($this->ID, null);
    }

    function edit_announce($announce)
    {
        if( !$this->ID )
            throw new DisParamException('参数不合法！');
        parent::update(array('announce'=>$announce));
        DisChanDataCache::set_chan_data($this->ID, null);
    }

    static function invite_user($channel_id, $user_id, $reason)
    {
        return DisChanApplicantData::insert($user_id, $channel_id, $reason, 0, 1);
    }

    function add_tag($tag)
    {
        if( !$this->ID )
            throw new DisParamException('参数不合法！');
        $id = DisChanTagData::insert($this->ID, $tag);
        DisChanDataCache::set_chan_data($this->ID, null);
        return $id;
    }

    function remove_tag($tag_id)
    {
        if( !$this->ID )
            throw new DisParamException('参数不合法！');
        DisChanTagData::delete($tag_id);
        DisChanDataCache::set_chan_data($this->ID, null);
    }

    static function list_chan_ids($tag = null, $page = 0, $count = 40)
    {
        if( $tag )
            $chans = DisChanTagData::list_channel_ids($tag, $page, $count);
        else
            $chans = parent::list_channels($page, $count, 'ID');

        $len = count($chans);
        for( $i = 0; $i < $len; $i ++ )
        {
            $chan_ids[] = $chans[$i]['ID'];
        }
        return $chan_ids;
    }

    static function parse_chans($chan_ids)
    {
        $chan_list = array();
        $len = count($chan_ids);

        for( $i = 0; $i < $len; $i ++ )
        {
            try
            {
                if( !is_int($chan_ids[$i]) && !is_string($chan_ids[$i]) )
                    continue;
                if( $chan_ids[$i] == '' )
                    continue;
                $chan_list[] = DisChannelCtrl::get_data($chan_ids[$i]);
            }
            catch( DisException $ex )
            {
//                $ex->trace_stack();
            }
        }
        return $chan_list;
    }
}
?>
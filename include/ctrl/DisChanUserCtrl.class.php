<?php
/**
 * @package: DIS.CTRL
 * @file   : DisChanUserCtrl.class.php
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

class DisChanUserCtrl extends DisChanUserData
{
    function __construct($user_id, $chan_id = 0)
    {
        parent::__construct($user_id, $chan_id);
    }

    static function get_data($user_id, $chan_id)
    {
//        pmRowMemcached::set_channel_user_data($chan_id, $user_id, null);
        $data = DisChanDataCache::get_chan_user_data($chan_id, $user_id);
        if( !$data )
        {
            $cu = new DisChanUserCtrl((int)$user_id, (int)$chan_id);
            if( !$cu->ID )
                throw new DisParamException("没有这个会员！");
            $data = $cu->info();
            DisChanDataCache::set_chan_user_data($chan_id, $user_id, $data);
        }
        return $data;
    }

    /**
     * 修改用户角色，嘉宾/主持人
     * @param integet $chan_id 用户的会员ID
     */
    function manage_edit_role($role, $chan_id = 0)
    {
        if( $chan_id > 0 )
            $this->load($chan_id);
        if( !in_array($role, array('member', 'editor')) )
            throw new DisException('参数不合法');
        $this->change_role($role);
    }

    function change_role($role)
    {
        if( !$this->ID )
            throw new DisException('参数不合法');
        if( is_string($role) )
            $role = parent::reg_role($role);
        else if( !is_int($role) || $role < 0 || $role > 4 )
            throw new DisException('无效的角色类型');

        if( !parent::change_role($role) )
            throw new DisException("修改失败！");
        $this->detail['role'] = $role;
        DisChanDataCache::set_chan_user_data($this->detail['chan_id'], $this->user_id,
                $this->info());
    }

    protected function set_rank($rank)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        if( parent::count_rank($this->detail['weight'], $rank) > 0 )
        {
            if( parent::incr_rank($this->detail['weight'], $rank) > 0 )
            {
                $_ids = parent::list_rank_ids($this->detail['weight'], $rank);
                $len = count($_ids);
                for( $i = 0; $i < $len; $i ++ )
                    DisChanDataCache::set_chan_user_data($_ids[$i]['chan_id'], $this->detail['user_id'], null);
            }
        }

        parent::set_rank($rank);
        DisChanDataCache::set_chan_user_data($this->detail['chan_id'], $this->detail['user_id'], null);
        DisUserVectorCache::set_subscribed_chan_ids($this->detail['user_id'], null);
    }

    function reset_weight($weight, $rank)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( $this->detail['weight'] == $weight && $this->detail['rank'] == $rank )
            return;
        if( $this->detail['weight'] != $weight )
            parent::set_weight($weight);
        $this->set_rank($rank);
    }

    function plus_weight()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        $rank = parent::get_max_rank($this->detail['weight'] + 1);
        parent::plus_weight();
        $this->set_rank($rank + 1);
    }

    function minus_weight()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        $rank = parent::get_max_rank($this->detail['weight'] - 1);
        parent::minus_weight();
        $this->set_rank($rank + 1);
    }

    function list_joined_ids()
    {
        if( !$this->user_id )
            throw new DisParamException('对象没有初始化！');

//        DisUserVectorCache::set_joined_channel_ids((int)$this->user_id, null);
        $join_chan_ids = DisUserVectorCache::get_joined_chan_ids((int)$this->user_id);
        if( !$join_chan_ids )
        {
            $join_chan_ids[0] = "#E#";
            $channels = $this->list_joined_channels();
            $count = count($channels);
            for( $i = 0; $i < $count; $i ++ )
                $join_chan_ids[$i] = $channels[$i]['chan_id'];
            DisUserVectorCache::set_joined_chan_ids((int)$this->user_id, $join_chan_ids);
        }

        if( $join_chan_ids[0] == "#E#" )
            $join_chan_ids = array();
        return $join_chan_ids;
    }

    function list_subscribed_ids()
    {
        if( !$this->user_id )
            throw new DisParamException('对象没有初始化！');

//        pmVectorMemcached::set_subscribed_chan_ids((int)$this->user_id, null);
        $chan_ids = DisUserVectorCache::get_subscribed_chan_ids((int)$this->user_id);
        if( !$chan_ids )
        {
            $chan_ids[0] = "#E#";
            $list = $this->list_subscribed_channels();
            $count = count($list);
            for( $i = 0; $i < $count; $i ++ )
                $chan_ids[$i] = $list[$i]['chan_id'];
            DisUserVectorCache::set_subscribed_chan_ids((int)$this->user_id, $chan_ids);
        }

        if( $chan_ids[0] == "#E#" )
            $chan_ids = array();
        return $chan_ids;
    }

    function list_subscribed_asc_ids()
    {
//        pmVectorMemcached::set_subscribed_channel_asc_ids((int)$this->ID, null);
        $chan_ids = DisUserVectorCache::get_subscribed_chan_asc_ids((int)$this->user_id);
        if( !$chan_ids )
        {
            $chan_ids[0] = "#E#";
            $list = $this->list_subscribed_asc_channels();
            $count = count($list);
            for( $i = 0; $i < $count; $i ++ )
                $chan_ids[$i] = $list[$i]['chan_id'];
            DisUserVectorCache::set_subscribed_chan_asc_ids((int)$this->user_id, $chan_ids);
        }

        if( $chan_ids[0] == "#E#" )
            $chan_ids = array();
        return $chan_ids;
    }

    function list_subscribed_weights()
    {
//        pmVectorMemcached::set_subscribed_channel_weights((int)$this->user_id, null);
        $weights = DisUserVectorCache::get_subscribed_chan_weights((int)$this->user_id);
        if( !$weights )
        {
            $weights = array();
            $list = $this->list_subscribed_channels();
            $count = count($list);

            for( $i = 0; $i < $count; $i ++ )
            {
                $weights[$list[$i]['chan_id']] = array('weight'=>$list[$i]['weight'], 'rank'=>$list[$i]['rank']);
            }
            DisUserVectorCache::set_subscribed_chan_weights((int)$this->user_id, $weights);
        }

//        if( $weights[0] == "#E#" )
//            $weights = array();
        return $weights;
    }

    function list_channel_roles()
    {
//        pmCacheUserVector::set_channel_roles((int)$this->user_id, null);
        $roles = DisUserVectorCache::get_chan_roles((int)$this->user_id);
        if( !$roles )
        {
            $list = $this->list_subscribed_channels();
            $count = count($list);
            for( $i = 0; $i < $count; $i ++ )
                $roles[$list[$i]['chan_id']] = $list[$i]['role'];
            DisUserVectorCache::set_chan_roles((int)$this->user_id, $roles);
        }
        return $roles;
    }

    function get_channel_role($chan_id)
    {
        if( !$this->user_id )
            throw new DisParamException('对象没有初始化！');
        $roles = $this->list_channel_roles();
        if( !isset($roles[$chan_id]) )
            return -1;
        return (int)$roles[$chan_id];
    }

    function get_channel_strrole($chan_id)
    {
        $role = $this->get_channel_role($chan_id);
        return $role < 0 ? 'stranger' : DisChanUserData::role((int)$role);
//        if( !$this->user_id )
//            throw new DisParamException('对象没有初始化！');
//        $roles = $this->list_channel_roles();
//        if( !isset($roles[$chan_id]) )
//            return 'stranger';
//        return DisChanUserData::role((int)$roles[$chan_id]);
    }
}
?>
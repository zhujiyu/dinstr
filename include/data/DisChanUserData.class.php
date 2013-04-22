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

class DisChanUserData extends DisDBTable //pmDBStaticTable
{
    var $user_id = 0;
    static $role_param = array('subscriber', 'member', 'editor', 'creator', 'superuser');

    function __construct($user_id, $channel_id = 0)
    {
        parent::__construct();
        $this->table = 'channel_users';
        $this->user_id = $user_id;
        if( (int)$user_id > 0 && (int)$channel_id > 0 )
            $this->load($channel_id);
    }

    function init($id, $slt = 'ID, channel_id, user_id, weight, `rank`, `role`, join_time')
    {
        $this->select("ID = $id", $slt);
        if ( !$this->ID )
            throw new DisParamException("不存在该数据");
        $this->detail['role'] = self::$role_param[$data['role']];
        return $this;
    }

    function load($channel_id, $slt = 'ID, channel_id, user_id, weight, `rank`, `role`, join_time')
    {
        if ( !$this->user_id || !$channel_id )
            throw new DisParamException('参数不合法');
        $this->select("channel_id = $channel_id and user_id = $this->user_id", $slt);
        return $this;
    }

    static function role($role)
    {
        if( $role < 0 || $role > 4 )
            throw new DisException('参数不合法');
        return self::$role_param[$role];
    }

    static function reg_role($role)
    {
        if( !in_array($role, self::$role_param) )
            throw new DisException('无效的角色类型');
        return array_search($role, self::$role_param);
    }

    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'user_id' :
                if( !uid_check($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'channel_id' :
            case 'weight' :
            case 'rank' :
            case 'opened' :
            case 'role' :
            case 'join_time' :
                if( !is_integer($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            default : return err(PMAIL_ERR_PARAM);
        }
        return err(PMAIL_SUCCEEDED);
    }

    protected function _check_num_param($param)
    {
        return in_array($param, array('rank'));
    }

    function insert($channel_id, $user_id, $role = 'subscriber', $rank = 0, $join_time = 0)
    {
        if( !$channel_id || !$user_id )
            throw new DisParamException("参数不合法！");
        $role = self::reg_role($role);
        return parent::insert(array('channel_id'=>$channel_id, 'user_id'=>$user_id, 'role'=>$role,
            'rank'=>$rank, 'join'=>$join_time));
    }

    function change_role($role)
    {
        if( $this->detail['role'] == $role )
            return 0;
        $str = "update $this->table set `role` = $role where ID = $this->ID";
        return parent::query($str) == 1;
    }

    function change_opened($opened)
    {
        return parent::update(array('opened'=>$opened));
    }

    function list_rank_ids($weight, $rank)
    {
        $str = "select ID, channel_id, `rank` from channel_users
            where user_id = $this->user_id and weight = $weight and `rank` >= $rank";
        return parent::load_datas($str);
    }

    function count_rank($weight, $rank)
    {
        return parent::count("from channel_users where user_id = $this->user_id and weight = $weight and `rank` = $rank");
    }

    function incr_rank($weight, $rank)
    {
        $str = "update channel_users set `rank` = `rank` + 1
            where user_id = $this->user_id and weight = $weight and `rank` >= $rank";
        return parent::query($str);
    }

    function get_max_rank($weight)
    {
        $str = "select max(`rank`) as max_rank from channel_users
            where user_id = $this->user_id and weight = $weight";
        $data = parent::load_line_data($str);
        if( $data )
            return $data[max_rank];
        else
            return 0;
    }

    protected function set_rank($rank)
    {
        $str = "update channel_users set `rank` = $rank where ID = $this->ID";
        return parent::query($str);
    }

    function set_weight($weight)
    {
        $str = "update channel_users set `weight` = $weight where ID = $this->ID";
        return parent::query($str);
    }

    function plus_weight()
    {
        $str = "update channel_users set weight = weight + 1 where ID = $this->ID";
        return parent::query($str);
    }

    function minus_weight()
    {
        $str = "update channel_users set weight = weight - 1 where ID = $this->ID";
        return parent::query($str);
    }

    function list_managed_channels()
    {
        if( !$this->user_id )
            throw new DisParamException("参数不合法！");
        $str = "select ID, channel_id, user_id, `role`, weight, `rank`, join_time
            from channel_users
            where user_id = $this->user_id and `role` > 1
            order by channel_id";
        return parent::load_datas($str);
    }

    function list_joined_channels()
    {
        if( !$this->user_id )
            throw new DisParamException("参数不合法！");
        $str = "select ID, channel_id, user_id, `role`, weight, `rank`, join_time
            from channel_users
            where user_id = $this->user_id and `role` > 0
            order by weight desc, `rank` desc";
        return parent::load_datas($str);
    }

    function list_subscribed_channels()
    {
        if( !$this->user_id )
            throw new DisParamException("参数不合法！");
        $str = "select ID, channel_id, user_id, `role`, weight, `rank`, join_time
            from channel_users
            where user_id = $this->user_id
                order by weight desc, `rank` desc";
        return parent::load_datas($str);
    }

    function list_subscribed_asc_channels()
    {
        if( !$this->user_id )
            throw new DisParamException("参数不合法！");
        $str = "select ID, channel_id, user_id, `role`, weight, `rank`, join_time
            from channel_users
            where user_id = $this->user_id order by channel_id";
        return parent::load_datas($str);
    }

    static function list_managers($channel_id)
    {
        if( !$channel_id )
            throw new DisParamException("参数不合法！");
        $str = "select ID, channel_id, user_id, `role`, weight, `rank`, join_time
            from channel_users
            where channel_id = $channel_id and `role` > 1";
        return parent::load_datas($str);
    }

    static function list_members($channel_id)
    {
        if( !$channel_id )
            throw new DisParamException("参数不合法！");
        $str = "select ID, channel_id, user_id, `role`, weight, `rank`, join_time
            from channel_users
            where channel_id = $channel_id and `role` > 0";
        return parent::load_datas($str);
    }

    static function list_subscribers($channel_id)
    {
        if( !$channel_id )
            throw new DisParamException("参数不合法！");
        $str = "select ID, channel_id, user_id, `role`, weight, `rank`, join_time
            from channel_users
            where channel_id = $channel_id";
        return parent::load_datas($str);
    }
}
?>
<?php
/**
 * @package: DIS.CTRL
 * @file   : DisUserParamCtrl.class.php
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

class DisUserParamCtrl extends DisUserParamData
{
    function  __construct($user_id = 0)
    {
        parent::__construct($user_id);
    }

    static function get_data($user_id)
    {
//        echo __CLASS__.":".__LINE__.'\n';
//        pmCacheUserData::set_user_param($user_id, null);
        $param = DisUserDataCache::get_user_param($user_id);
        if( !$param )
        {
            $user = new DisUserParamCtrl($user_id);
            $param = $user->info();
            DisUserDataCache::set_user_param($user_id, $param);
        }
        return $param;
    }

    function pay_money($imoney)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);

        parent::pay_money($imoney);
        DisUserDataCache::set_user_param($this->ID, $this->detail);
    }

    function increase($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::increase($param, $step);
        DisUserDataCache::set_user_param($this->ID, $this->detail);
    }

    function reduce($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::reduce($param, $step);
        DisUserDataCache::set_user_param($this->ID, $this->detail);
    }
}
?>
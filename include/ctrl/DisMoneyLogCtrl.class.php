<?php
/**
 * @package: DIS.CTRL
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisMoneyLogCtrl extends DisMoneyLogData
{
    function  __construct($user_id)
    {
        parent::__construct($user_id);
    }

    function hasCharge()
    {
        $data = parent::last_log();
        $log_time = $data ? strtotime($data['log_time']) : 0;
        if( $log_time >= mktime(0, 0, 0) )
            return true;
        return false;
    }

    function list_logs()
    {
        $logs = DisUserDataCache::get_user_logs($this->user_id);
        if( !$logs )
            $logs = parent::list_logs();
        DisUserDataCache::set_user_logs($this->user_id , $logs);
        return $logs;
    }

    function recharge()
    {
        $param = DisUserParamCtrl::get_data($this->user_id);
        if( (int)$param['imoney'] > 1000 )
        {
            $str = '你的金币余额已经超过了1000金币，你可以先发些资讯再来领取！';
            throw new DisException($str);
        }

        if( $this->hasCharge() )
        {
            $str = '你已经领取了今天的奖励金币，不能重复领取！';
            throw new DisException($str);
        }

        parent::insert();
        $uparam = new DisUserParamCtrl($this->user_id);
        $uparam->increase('imoney', 100);
    }
}
?>

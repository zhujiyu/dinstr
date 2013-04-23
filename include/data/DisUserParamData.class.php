<?php
/**
 * @package: DIS.DATA
 * @file   : DisUserParamData.class.php
 * @abstract  : 用户数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisUserParamData extends DisDBTable
{
    function  __construct($user_id = 0)
    {
        $this->table = "user_params";
        parent::__construct();
        if( $user_id > 0 )
            $this->init($user_id);
    }

    function init($user_id)
    {
        $str = "select * from user_params where ID = $user_id";
        $data = parent::load_line_data($str);
        if( $data )
        {
            $this->ID = $data['ID'];
            $this->detail = $data;
        }
        else
            $this->ID = 0;
        return $this;
    }

    protected function _check_num_param($num_param)
    {
        return in_array($num_param, array('imoney', 'online_times',
            'follow_num', 'fans_num', 'msg_num',
            'note_num', 'head_num', 'interest_num', 'approved_num', 'collect_num',
            'join_num', 'subscribe_num', 'applicant_num', 'create_num',
            'reply_notice', 'head_notice', 'msg_notice', 'system_notice', 'fans_notice'));
    }

    // 更新用户信息时，检验各字段的值是否合法
    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'ID' :
                if ( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'imoney' :
                if ( !is_numeric($value) )
                    return err(DIS_ERR_PARAM);
                break;
            default :
                return err(DIS_ERR_PARAM);
        }
        return err(DIS_SUCCEEDED);
    }

    function insert($id)
    {
        $str = "insert into user_params (ID, imoney) values ($id, 0)";
        parent::query($str);
        return $this->init($id);
    }

    function pay_money($imoney)
    {
        $str = "update user_params set imoney = imoney - $imoney
            where ID = $this->ID";
        if( parent::query($str) != 1 )
            throw new DisDBException('支付金币失败');
        $this->detail['imoney'] = (int)$this->detail['imoney'] - (int)$imoney;
    }

    /**
     * 更新当前的通知
     * @return array 返回消息通知的数组
     */
    function update_notice()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        $str = "select reply_notice, fans_notice, msg_notice, note_notice, system_notice
            from user_params where ID = $this->ID";
        $notices = parent::load_line_data($str);

        foreach( $notices as $name => $value )
            $this->detail[$name] = $value;
        return $notices;
    }

//    static function notice_name($name, $notice, $count = 1)
//    {
//        if( !in_array($notice, array('reply_notice', 'theme_notice', 'system_notice',
//            'fans_notice', 'msg_notice')) || $count == 0 )
//            throw new DisParamException('参数不合法！');
//
//        $str = "update users set $notice = $notice + $count where username = '$name'";
//        return self::query($str) == 1;
//    }

    /**
     * 通知用户
     * @param string $notice 通知项目
     * @param integer $count 改变值，不是绝对值
     * @return integer 成功返回1，失败返回0，是执行SQL语句影响的行数
     */
    function notice($notice, $count = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !in_array($notice, array('reply_notice', 'flighty_notice', 'atme_notice', 'fans_notice', 'msg_notice'))
                || $count == 0 )
            throw new DisParamException('参数不合法！');

        $value = max(0, $this->detail[$notice] + $count);
        if( $value == $this->detail[$notice] )
            throw new DisParamException('参数不合法！');
        $str = "update users set $notice = $value where ID = $this->ID";
        $r = self::query($str);
        if( $r == 1 )
            $this->detail[$notice] = $value;
        return $r;
    }
}
?>
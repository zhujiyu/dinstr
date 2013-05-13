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

class DisChanApplicantData extends DisDBTable
{
    function  __construct($id = null)
    {
        $this->table = "chan_applicants";
        parent::__construct();
        if( $id && is_int($id) )
            $this->load($id);
    }

    function load($id)
    {
        $str = "select ID, chan_id, user_id, status, reason
            from $this->table where ID = $id";
        $data = parent::load_line_data($str);

        if( $data )
        {
            $this->ID = $id;
            $this->detail = $data;
        }
        else
            $this->ID = 0;

        return $this;
    }

    function insert($user_id, $chan_id, $reason)
    {
        if( !$user_id || !$chan_id || !$reason )
            throw new DisParamException("参数不合法！");

	    $str = "insert into $this->table (user_id, chan_id, reason)
	        values ($user_id, $chan_id, '$reason')";
        if( parent::query($str) != 1 )
            throw new DisDBException("插入失败！");

        $this->ID = parent::last_insert_Id();
        return $this->ID;
    }

    function exist($user_id, $chan_id)
    {
        $str = "from $this->table where user_id = $user_id and chan_id = $chan_id and status = 'untreated'";
        return parent::count($str) > 0;
    }

    function remove()
    {
        if( !$this->ID )
            throw new DisParamException("参数不合法！");
        $str = "delete from $this->table where ID = $this->ID";
        return parent::check_query($str, 1);
//        return (parent::query($str) === 1);
    }

    function accept()
    {
        if( !$this->ID )
            throw new DisParamException("参数不合法！");
        $str = "update $this->table set status = 'accept' where ID = $this->ID";
        return parent::check_query($str, 1);
//        return parent::query($str) == 1;
    }

    function refuse()
    {
        if( !$this->ID )
            throw new DisParamException("参数不合法！");
        $str = "update $this->table set status = 'refuse' where ID = $this->ID";
        return parent::query($str) == 1;
    }

//    static function list_applicants($chan_id, $page = 0, $count = 40)
//    {
//        $str = "select ID, chan_id, user_id, `reason`, status, apply_time
//	        from chan_applicants where chan_id = $chan_id
//            order by ID desc limit ".$page*$count.", $count";
//	    return parent::load_datas($str);
//    }

//    static function list_apply_channel_applicants($chan_id)
    static function list_apply_channel_applicants($chan_id)
    {
        $str = "select ID, chan_id, user_id, `reason`, status, apply_time
	        from chan_applicants
            where chan_id = $chan_id and status = 0";
	    return parent::load_datas($str);
    }

    static function list_apply_channels($user_id)
    {
        $str = "select ID, chan_id, user_id, `reason`, status, apply_time
	        from chan_applicants where user_id = $user_id order by ID desc";
	    return parent::load_datas($str);
    }
}
?>
<?php
/**
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisUserLoginData extends DisDBTable
{
    var $user_id;

    function  __construct($user_id = 0)
    {
        $this->user_id = $user_id;
        $this->table = "user_logins";
        parent::__construct();
    }

    function insert($user_id)
    {
        $this->user_id = $user_id;
        $str = "insert into $this->table (user_id, login)
            values ($this->user_id, unix_timestamp())";
        $r = parent::query($str);
        if( !$r )
            throw new DisDBException('插入失败');
        $this->ID = parent::last_insert_Id();
        return $this->ID;
    }

    function last_login()
    {
        $str = "select ID, login, logout from $this->table
            where user_id = $this->user_id order by ID desc limit 1";
        $data = parent::load_line_data($str);

        if( $data )
        {
            $this->ID = $data['ID'];
            $this->detail = $data;
        }
        else
            $this->ID = 0;
//        return $data ? $data['login'] : 0;
    }

    function checkin()
    {
        $str = "update $this->table set logout = from_unixtime(unix_timestamp()) where ID = $this->ID";
        parent::query($str);
        $this->detail['logout'] = time();
    }
}
?>

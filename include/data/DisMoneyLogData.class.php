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

class DisMoneyLogData extends DisObject
{
    var $table;
    var $user_id;

    function  __construct($user_id)
    {
        $this->table = "imoney_logs";
        parent::__construct();
        $this->user_id = $user_id;
    }

    function insert()
    {
        $str = "insert into $this->table (user_id, imoney) values ($this->user_id, 100)";
        if( !DisDBTable::query($str) )
            throw new DisDBException('插入数据失败');
        return DisDBTable::last_insert_Id();
    }

    function last_log()
    {
        $str = "select * from $this->table where user_id = $this->user_id
            order by ID desc limit 1";
        return DisDBTable::load_line_data($str);
    }

    function list_logs()
    {
        $str = "select * from $this->table where user_id = $this->user_id order by ID desc limit 5";
        return DisDBTable::load_datas($str);
    }
}
?>

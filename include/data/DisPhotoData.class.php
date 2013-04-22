<?php
/**
 * @package: DIS.DATA
 * @file   : DisPhotoData.class.php
 * 网络图片
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisPhotoData extends DisDBTable
{
    // 构造函数
    function __construct($id = null)
    {
        $this->table = "photos";
        parent::__construct();
        if( $id && is_integer($id) )
            $this->load($id);
    }

    function load($id, $slt = "ID, user_id, `small`, `big`, create_time")
    {
        $str = "select $slt from $this->table where ID = $id";
        $data = parent::load_line_data($str);
        if( $data )
        {
            $this->ID = $data['ID'];
            $this->detail = $data;
        }
    }

    function insert($big, $small, $user_id)
    {
        $str = "insert into $this->table (`small`, `big`, user_id) values ('$small', '$big', $user_id)";
        DisDBTable::query($str);
        $this->ID = DisDBTable::last_insert_Id();
        $this->load($this->ID);
        return $this->ID;
    }

    protected function _check_num_param($param)
    {
        return in_array($param, array('quote'));
    }
}
?>
<?php
/**
 * @package: PMAIL.DB
 * @file   : pmDBStaticTable
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisDBStaticTable extends DisObject
{
    static function insert($str)
    {
        if( DisDBTable::query($str) != 1 )
            throw new DisDBException('插入收藏数据失败！');
        return DisDBTable::last_insert_Id();
    }

    static function get_id($str)
    {
        $data = DisDBTable::load_line_data($str);
        if( $data )
            return $data['ID'];
        else
            return 0;
    }

    static function delete($id, $table)
    {
        $str = "delete from $table where ID = $id";
        return DisDBTable::query($str);
    }
}
?>
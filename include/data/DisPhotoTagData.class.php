<?php
/**
 * @package: DIS.DATA
 * @file   : DisPhotoTagData.class.php
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

class DisPhotoTagData extends DisObject
{
    static function load($ID)
    {
        $str = "select ID, photo_id, tag from photo_tags where photo_id = $ID";
	    return DisDBTable::load_line_data($str);
    }

    static function insert($photo_id, $tag)
    {
	    $str = "insert into photo_tags (photo_id, tag) values ($photo_id, '$tag')";
	    $r = DisDBTable::query($str);
        if( $r != 1 )
            throw new soDBException('添加标签失败！');
        return DisDBTable::last_insert_Id();
    }

    static function delete($mid)
    {
        return DisDBTable::query("delete from photo_tags where ID = $mid");
    }

    static function list_photo_ids($tag, $page = 0, $count = 20)
    {
        $str = "select ID, photo_id, tag from photo_tags where tag = '$tag'
            order by ID desc limit " . $page * $count . ", $count";
	    return DisDBTable::load_datas($str);
    }

    static function list_tags($photo_id)
    {
        $str = "select ID, photo_id, tag from photo_tags where photo_id = $photo_id";
        return DisDBTable::load_datas($str);
    }
}
?>
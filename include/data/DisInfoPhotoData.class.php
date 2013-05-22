<?php
/**
 * @package: DIS.DATA
 * @file   : DisNotePhotoData.class.php
 * @abstract  : 信息结点
 *
 * 各个函数的参数检查不严格，不做数据完整性一致性检查，甚至数据格式的检查也不完整
 * 上层调用的时候，自己保证数据
 *
 * @abstract  : 邮件数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisInfoPhotoData extends DisObject
{
    static function insert($note_id, $photo_id, $rank = 0, $desc = '')
    {
        $str = "insert into info_photos (note_id, photo_id, `rank`, `desc`)
            values ($note_id, $photo_id, $rank, '$desc')";
        return DisDBTable::check_query($str, 1);
    }

    static function list_info_photos($note_id)
    {
        $str = "select ID, note_id, photo_id, `rank`, `desc`
            from info_photos where note_id = $note_id";
        return DisDBTable::load_datas($str);
    }

    static function get_data($id)
    {
        $str = "select ID, note_id, photo_id, `rank`, `desc`
            from info_photos where ID = $id";
        return DisDBTable::load_line_data($str);
    }
}
?>
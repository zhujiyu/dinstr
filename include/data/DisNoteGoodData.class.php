<?php
/**
 * @package: DIS.DATA
 * @file   : DisNoteFlowData.class.php
 * @abstract  : 信息结点
 *
 * 各个函数的参数检查不严格，不做数据完整性一致性检查，甚至数据格式的检查也不完整
 * 上层调用的时候，自己保证数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisNoteGoodData extends DisObject
{
    static function insert($mail_id, $good_id, $rank = 0, $desc = '')
    {
        $str = "insert into mail_goods (mail_id, good_id, `rank`, `desc`)
            values ($mail_id, $good_id, $rank, '$desc')";
        return DisDBTable::query($str) == 1;
    }

    static function list_mail_goods($mail_id)
    {
        $str = "select ID, mail_id, good_id, `rank`, `desc`
            from mail_goods where mail_id = $mail_id";
        return DisDBTable::load_datas($str);
    }

    static function get_data($id)
    {
        $str = "select ID, mail_id, good_id, `rank`, `desc`
            from mail_goods where ID = $id";
        return DisDBTable::load_line_data($str);
    }
}
?>
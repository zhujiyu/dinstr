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

class DisNoteKeywordData extends DisObject
{
    static function insert($mail_id, $keyword)
    {
        if( !$nid || !$keyword )
            throw new DisParamException("输入参数不合法！");
        $str = "insert into mail_keywords (mail_id, keyword) values ($mail_id, '$keyword')";
        return DisDBTable::query($str) == 1;
    }

    static function delete($mail_id)
    {
        if( !$mail_id )
            throw new DisParamException("输入参数不合法！");
        $str = "delete from mail_keywords where mail_id = $mail_id";
        return DisDBTable::query($str);
    }

    static function list_releases($keyword, $page = 0, $size = 20)
    {
        $str = "select k.mails_id, r.ID as release_id
            from mail_keywords as k, mails_publishs as r
            where k.keyword = '$keyword' and r.mails_id = k.mails_id
            group by k.ID order by r.ID desc limit ".$page*$size.", ".$size;
        return DisDBTable::load_datas($str);
    }

    static function list_infos($keyword, $page = 0, $size = 20)
    {
        $str = "select ID, mail_id, keyword from mail_keywords where keyword = '$keyword'
            order by mail_id desc limit ".$page*$size.", ".$size."";
        return DisDBTable::load_datas($str);
    }
}
?>
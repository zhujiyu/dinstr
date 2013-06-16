<?php
/**
 * @file : user.inc.php
 * @abstract 用户视图
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-05-23
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

function parse_infos($head_ids, $user_id)
{
    $len = count($head_ids);
    for( $i = 0; $i < $len; $i ++ )
    {
        $head = DisHeadCtrl::head($head_ids[$i]);
        $info = DisNoteCtrl::get_note_view($head->attr('note_id'));
        $info['content'] = strip_tags($info['content']);
        $info['head'] = $head->info();
        $info['head']['status'] = $head->check_status($user_id);
        $info_list[] = $info;
    }
    return $info_list;
}

?>

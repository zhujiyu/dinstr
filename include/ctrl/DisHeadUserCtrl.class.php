<?php
/**
 * @package: DIS.CTRL
 * @file   : DisHeadUserCtrl.class.php
 * @abstract  :
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisHeadUserCtrl extends DisHeadUserData
{
    static function get_data($approve_id)
    {
        $approve = DisNoteDataCache::get_approve_data($approve_id);
        if( !$approve )
        {
            $approve = parent::load($approve_id);
            DisNoteDataCache::set_approve_data($approve_id, $approve);
        }
        return $approve;
    }
}
?>
<?php
/**
 * @package: DIS.CTRL
 * @file   : DisUserRelationCtrl.class.php
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

class DisUserRelationCtrl extends DisUserRelationData
{
    static function get_data($relation_id)
    {
//        pmCacheUserData::set_relation_data($relation_id, null);
        $relation = DisUserDataCache::get_relation_data($relation_id);
        if( !$relation )
        {
            $relation = parent::load($relation_id);
            DisUserDataCache::set_relation_data($relation_id, $relation);
        }
        return $relation;
    }
}

?>
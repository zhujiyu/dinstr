<?php
/**
 * @package: DIS.CTRL
 * @file   : DisChanApplicantCtrl.class.php
 * @abstract  : 频道管理
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisChanApplicantCtrl extends DisChanApplicantData
{
    function  __construct($app_id = null)
    {
        parent::__construct();
        parent::__construct((int)$app_id);
//        if( $app_id && is_int($app_id) )
//        {
//            $this->ID = $app_id;
//            $this->detail = self::get_data($app_id);
//        }
    }

    static function get_data($app_id)
    {
        $apply = DisChanDataCache::get_applicant_data($app_id);
        if( !$apply )
        {
            $capply = new DisChanApplicantData((int)$app_id);
            if( !$capply->ID )
                throw new DisException("不存在该申请 $app_id");
            $apply = $capply->info();
            DisChanDataCache::set_applicant_data($app_id, $apply);
        }
        return $apply;
    }
}
?>
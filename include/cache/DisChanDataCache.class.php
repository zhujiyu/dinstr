<?php
/**
 * @file: DisChanDataCache.class.php
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisChanDataCache extends DisRowCache
{
    static function get($key)
    {
        return parent::get('mail-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('mail-'.$key, $value);
    }

    static function get_applicant_data($app_id)
    {
        $key = "applicant-$app_id";
        return self::get($key);
    }

    static function set_applicant_data($app_id, $data)
    {
        $key = "applicant-$app_id";
        self::set($key, $data);
    }

    static function get_channel_user_data($channel_id, $user_id)
    {
        $key = "curd-$channel_id-$user_id";
        return self::get($key);
    }

    static function set_channel_user_data($channel_id, $user_id, $member_data)
    {
        $key = "curd-$channel_id-$user_id";
        self::set($key, $member_data);
    }

    static function get_channel_data($channel_id)
    {
        $key = "odd-".$channel_id;
        return self::get($key);
    }

    static function set_channel_data($channel_id, $data)
    {
        $key = "odd-".$channel_id;
        self::set($key, $data);
    }
}
?>

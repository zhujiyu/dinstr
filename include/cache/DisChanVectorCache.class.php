<?php
/**
 * @file: DisChanVectorCache.class.php
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisChanVectorCache extends DisVectorCache
{
    static function get($key)
    {
        return parent::get('mail-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('mail-'.$key, $value);
    }

    static function get_channel_flow_ids($channel_id)
    {
        $key = "oafs-$channel_id";
        return self::get($key);
    }

    static function set_channel_flow_ids($channel_id, $flow_ids)
    {
        $key = "oafs-$channel_id";
        self::set($key, $flow_ids);
    }

    static function get_channel_flows($channel_id, $period, $flag)
    {
        $key = "vcfs-$channel_id-$period-$flag";
        return self::get($key);
    }

    static function set_channel_flows($channel_id, $period, $flag, $vflows)
    {
        $key = "vcfs-$channel_id-$period-$flag";
//        $key = "vcfs-$channel_id-$flag-$period";
        self::set($key, $vflows);
    }

    static function get_manager_ids($channel_id)
    {
        $key = "eid-".$channel_id;
        return self::get($key);
    }

    static function set_manager_ids($channel_id, $manager_ids)
    {
        $key = "eid-".$channel_id;
        self::set($key, $manager_ids);
    }

    static function get_subscribed_user_ids($channel_id)
    {
        $key = 'sid-'.$channel_id;
        return self::get($key);
    }

    static function set_subscribed_user_ids($channel_id, $mids)
    {
        $key = 'sid-'.$channel_id;
        self::set($key, $mids);
    }

    static function get_joined_user_ids($channel_id)
    {
        $key = 'muid-'.$channel_id;
        return self::get($key);
    }

    static function set_joined_user_ids($channel_id, $user_ids)
    {
        $key = 'muid-'.$channel_id;
        self::set($key, $user_ids);
    }
}
?>

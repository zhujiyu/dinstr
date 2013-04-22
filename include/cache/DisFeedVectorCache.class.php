<?php
/**
 * @file: DisFeedVectorCache.class.php
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisFeedVectorCache extends DisRowCache
{
    static function get($key)
    {
        return parent::get('feed-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('feed-'.$key, $value);
    }

    static function get_notes($flag)
    {
        $key = "ns-$flag";
        return self::get($key);
    }

    static function set_notes($flag, $notes)
    {
        $key = "ns-$flag";
        self::set($key, $notes);
    }

    static function get_hist_notes($flag)
    {
        $key = "histns-$flag";
        return parent::get($key);
    }

    static function set_hist_notes($flag, $notes)
    {
        $key = "histns-$flag";
        parent::set($key, $notes);
    }

    static function get_common_hists($start, $end)
    {
        $key = "guest-$start-$end";
        return self::get($key);
    }

    static function set_common_hists($start, $end, $flow_ids)
    {
        $key = "guest-$start-$end";
        self::set($key, $flow_ids);
    }

    static function get_flow_ids($chan_id, $start, $end)
    {
        $key = "cfids-$chan_id-$start-$end";
        return self::get($key);
    }

    static function set_flow_ids($chan_id, $start, $end, $flow_ids)
    {
        $key = "cfids-$chan_id-$start-$end";
        self::set($key, $flow_ids);
    }
}
/*
class DisFeedDataCache extends DisRowCache
{
    static function get($key)
    {
        return parent::get('feed-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('feed-'.$key, $value);
    }

    static function get_value_flows($channel_id, $period)
    {
        $key = "vfs-$channel_id-$period";
        return self::get($key);
    }

    static function set_value_flows($channel_id, $period, $flows)
    {
        $key = "vfs-$channel_id-$period";
        self::set($key, $flows);
    }
}
*/
?>
<?php
/**
 * @package: DIS.CACHE
 * @file   : pmRowMemcached.php
 * @abstract  : 缓存行数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisRowCache extends DisMemcached
{
    static function get($key)
    {
        return parent::get('r-'.$key);
    }

    static function set($key, $value)
    {
        parent::set('r-'.$key, $value);
    }

//    static function get_good_data($good_id)
//    {
//        $key = "gn-$good_id";
//        return self::get($key);
//    }
//
//    static function set_good_data($good_id, $good)
//    {
//        $key = "gn-$good_id";
//        self::set($key, $good);
//    }
//
//    static function get_photo_data($photo_id)
//    {
//        $key = "pn-$photo_id";
//        return self::get($key);
//    }
//
//    static function set_photo_data($photo_id, $photo)
//    {
//        $key = "pn-$photo_id";
//        self::set($key, $photo);
//    }
//
//    static function get_notice($notice_id)
//    {
//        $key = "ns-$notice_id";
//        return self::get($key);
//    }
//
//    static function set_notice($notice_id, $notices)
//    {
//        $key = "ns-$notice_id";
//        self::set($key, $notices);
//    }
}

if( !DisRowCache::$_memcached && function_exists("memcache_connect") )
{
    DisRowCache::$_memcached = memcache_connect(DisConfigAttr::$row_memcached['host'],
            DisConfigAttr::$row_memcached['port']);
}
?>
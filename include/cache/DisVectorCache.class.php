<?php
/**
 * @package: DIS.CACHE
 * @file   : pmVectorMemcached.php
 * @abstract  : 缓存矢量数据
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisVectorCache extends DisMemcached
{
    static function get($key)
    {
        return parent::get('v-'.$key);
    }

    static function set($key, $value)
    {
        parent::set('v-'.$key, $value);
    }
}

if( !DisVectorCache::$_memcached && function_exists("memcache_connect") )
{
//    echo __FILE__.":".__LINE__."\n";
    DisVectorCache::$_memcached = memcache_connect(DisConfigAttr::$vector_memcached['host'],
            DisConfigAttr::$vector_memcached['port']);
}
?>
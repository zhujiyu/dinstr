<?php
/**
 * @package: DIS.CACHE
 * @file   : DisIMemcached.class.php
 * @abstract  : memcached 基础类
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisIMemcached extends DisObject
{
    static $_memcached = null;

    static function get($key)
    {
        if( self::$_memcached == null )
            throw new DisParamException('没有建立缓存连接');
        if( $key == null || $key == '' )
            throw new DisParamException('主键不能为空');
        return self::$_memcached->get($key);
    }

    static function set($key, $value)
    {
        if( self::$_memcached == null )
            throw new DisParamException('没有缓存连接');
        if( $key == null || $key == '' )
            throw new DisParamException('主键不能为空');
        self::$_memcached->set($key, $value);
    }

    static function push($key, $value)
    {
        if( self::$_memcached == null )
            throw new DisParamException('没有缓存连接');
        if( $key == null || $key == '' )
            throw new DisParamException('主键不能为空');

        $arr = self::$_memcached->get($key);
        if ( $arr == null )
            return ;
        array_unshift($arr, $value);
        self::$_memcached->set($key, $arr);
    }

    static function drop($key, $value)
    {
        if( self::$_memcached == null )
            throw new DisParamException('没有缓存连接');
        if( $key == null || $key == '' )
            throw new DisParamException('主键不能为空');

        $arr = self::$_memcached->get($key);
        if ( $arr == null )
            return ;
        $idx = array_search($value, $arr);
        array_splice($arr, $idx, 1);
        self::$_memcached->set($key, $arr);
    }

    static function incr($key)
    {
        if( self::$_memcached == null )
            throw new DisParamException('没有建立缓存连接');
        if( $key == null || $key == '' )
            throw new DisParamException('主键不能为空');
        return self::$_memcached->incr($key);
    }
}
?>
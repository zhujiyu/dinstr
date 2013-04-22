<?php
/**
 * @package: DIS.CACHE
 * @file  : pmMemcachedMock.php
 * @abstract  : 用于对使用memcached的代码进行单元测试
 *
 * Memcache::connect -- 打开一个到Memcache的连接
 * Memcache::pconnect -- 打开一个到Memcache的长连接
 * Memcache::close -- 关闭一个Memcache的连接
 * Memcache::set -- 保存数据到Memcache服务器上
 * Memcache::get -- 提取一个保存在Memcache服务器上的数据
 * Memcache::replace -- 替换一个已经存在Memcache服务器上的项目（功能类似Memcache::set）
 * Memcache::delete -- 从Memcache服务器上删除一个保存的项目
 * Memcache::flush -- 刷新所有Memcache服务器上保存的项目（类似于删除所有的保存的项目）
 * Memcache::getStats -- 获取当前Memcache服务器运行的状态
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class soMemcachedMock
{
    static $_mem = array();

    function get($param)
    {
        if( isset(self::$_mem[$param]) )
            return self::$_mem[$param];
        else
            return null;
    }

    function set($param, $value)
    {
        self::$_mem[$param] = $value;
    }
}
?>

<?php
/**
 * @package: DIS.TEST.CORE
 * @file   : DisIAdapter.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

interface DisIAdapter
{
    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE);
    function query($sql, $type = '');

    function select_db($dbname, $halt = TRUE);
    function fetch_array($query, $result_type = MYSQL_ASSOC);
    function num_rows($query);
    function insert_id();
    function close();
}
?>
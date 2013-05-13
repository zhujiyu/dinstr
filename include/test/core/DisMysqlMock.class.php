<?php
/**
 * @package: DIS.TEST.CORE
 * @file   : DisMysqlMock.class.php
 * @abstract   :  Mock of database adapter for PHPUnit testing.
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisMysqlMock implements DisIAdapter
{
    var $id = 0;

    //put your code here
    function connect($dbhost, $dbuser, $dbpw, $dbname = '') //, $pconnect = 0, $halt = TRUE)
    {
        $str = "server=$dbhost;User Id=$dbuser;password=$dbpw;database=$dbname";
        echo "<p><b>Connect</b>: $str</p>";
        return $str;
    }

    function query($sql, $type = '')
    {
        echo "<p><b>Query</b>:$sql $type</p>";
        return $sql;
    }

    function select_db($dbname, $halt = TRUE)
    {
        echo "<p><b>select_db</b>:$dbname $halt</p>";
        return $dbname;
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC)
    {
        $str = print_r($query, true);
        echo "<p><b>select_db</b>: $str </p>";
        return $str;
    }

    function num_rows($query)
    {
        $str = print_r($query, true);
        echo "<p><b>num_rows</b>: $str </p>";
        return $str;
    }

    function insert_id()
    {
        echo "<p><b>insert_id</b>: $this->id </p>";
        return $this->id;
    }

    function close()
    {
        $str = "Mysql connection closed.";
        echo "<p><b>close</b>: Mysql connection closed. </p>";
        return $str;
    }
}
?>

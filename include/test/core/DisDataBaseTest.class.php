<?php
/**
 * @package: DIS.TEST.CORE
 * @file   : DisDataBaseTest.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

// /usr/share/php/docs/PHPUnit/PHPUnit/Samples
// /usr/share/php/PHPUnit
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Extensions/Database/DataSet/FlatXmlDataSet.php';

DisVectorCache::$_memcached = new DisMemcachedMock();
DisRowCache::$_memcached    = new DisMemcachedMock();
DisDBTable::$readPDO  = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
DisDBTable::$writePDO = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');

abstract class DisDataBaseTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $pdo;
    protected $default_data_file;

    function  __construct($sqls = null)
    {
        $this->pdo = DisDBTable::$readPDO;
        if( $sqls == null )
            return;
        $len = count($sqls);
        for( $i = 0; $i < $len; $i ++ )
            $this->pdo->exec($sqls[$i]);
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->pdo, 'test');
    }

    protected function getDataSet()
    {
        return $this->_getDataSet($this->default_data_file);
    }

    protected function _getDataSet($file, $path = null)
    {
        if( $path == null )
            $path = dirname(__FILE__)."/../res/";
        if( !file_exists($path) )
            throw new DisException("测试数据源路径 $path 不存在");

        if( file_exists($path.$file) )
            $path = $path.$file;
        else if( file_exists("$path../$file") )
            $path = "$path../$file";
        else
            throw new DisParamException("测试数据源文件 $path $file 不存在");

        return $this->createFlatXMLDataSet($path);
    }

    protected function _getDBRow($table, $slt, $whr)
    {
        return DisDBTable::load_line_data("select $slt from $table where $whr");
    }

    protected function _getDatabaseTable($table, $slt)
    {
        return new PHPUnit_Extensions_Database_DataSet_QueryTable($table,
                "select $slt from $table", $this->getConnection());
    }

    protected function _getXmlTable($table, $file = null)
    {
        if( $file == null )
            $file = $this->default_data_file;
        return $this->_getDataSet($file)->getTable($table);
    }
}
?>

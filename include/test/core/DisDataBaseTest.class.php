<?php
/**
 * @package: DIS.TEST.CORE
 * @file   : DisDataBaseTest.class.php
 * Description of DisDataBaseTest
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
DisRowCache::$_memcached = new DisMemcachedMock();
DisDBTable::$readPDO = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
DisDBTable::$writePDO = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');

abstract class DisDataBaseTest extends PHPUnit_Extensions_Database_TestCase
{
//    protected $mock;
//    protected $columns;
//    protected $table;
    protected $pdo;
    protected $default_data_file;

    function  __construct()
    {
        $this->pdo = DisDBTable::$readPDO;
    }

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->pdo, 'test');
    }

    protected function getDataSet()
    {
        return $this->_getDataSet($this->default_data_file);
    }

    protected function _getDataSet($file)
    {
        if( file_exists($file) )
            $path = $file;
        else if( file_exists("res/$file") )
            $path = "res/$file";
        else if( file_exists(dirname(__FILE__)."res/$file") )
            $path = dirname(__FILE__)."res/$file";
        else
            throw new DisException('文件不存在');
        return $this->createFlatXMLDataSet($path);
    }

//    protected function _getDatabaseTable($table = null, $query = null)
//    {
//        if( $table == null )
//            $table = $this->table;
//        if( $query == null )
//            $query = "select $this->columns from $table";
//        return new PHPUnit_Extensions_Database_DataSet_QueryTable($table, $query,
//            $this->getConnection());
//    }
//
//    protected function _getXmlTable($file = null, $table = null)
//    {
//        if( !$file )
//            $file = $this->default_data_file;
//        if( !$table )
//            $table = $this->table;
//        return $this->_getDataSet($file)->getTable($table);
//    }
}

?>

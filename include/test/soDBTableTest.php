<?php
/**
 * Description of soDBTableTest
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
$file = "common.inc.php";
while( !file_exists($file) )
    $file = "../".$file;
require_once ( $file );
//require_once dirname(__FILE__).'/../../common.inc.php';

class soDBTableMock extends DisDBTable
{
    function  __construct()
    {
        $this->table = "test_mock_table";
        parent::__construct();
    }

    function add_new_data($data)
    {
        return $this->insert($data);
    }

    static function create_mock_table(PDO $pdo)
    {
        $str = "create table test_mock_table
            (
                ID int auto_increment primary key,
                name varchar(255),
                years int default 0,
                unique key(name)
            )
            ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000000;";
        $pdo->query($str);
    }
}

class soDBTableTest extends DisDataBaseTest
{
    public function  __construct()
    {
        parent::__construct();

        soDBTableMock::create_mock_table($this->pdo);
        $this->mock = new soDBTableMock();
        $this->table = "test_mock_table";
        $this->columns = "ID, name, years";
    }

    protected function getDataSet()
    {
        return $this->_getDataSet('dbtable_test_mock.xml');
    }

    function testLoadLineData()
    {
        $r = soDBTable::load_line_data("select ID, name, years from test_mock_table where ID = 1593490", null, $this->pdo);
        $this->assertEquals($r['ID'], 1593490);
        $r = soDBTable::load_line_data("select ID, name, years from test_mock_table where ID = ? ", array('1593490'), $this->pdo);
        $this->assertEquals($r['ID'], 1593490);
    }

    function testLoadDatas()
    {
        $data = soDBTable::load_datas("select ID, name, years from test_mock_table");
        $this->assertEquals($data[1]['name'], 'zhujiyu');
    }

    /**
     * @expectedException soDBException
     */
    function testLoadDatasException()
    {
        $count = soDBTable::count("from test_mock_tablesews");
        $data = soDBTable::load_datas("select ID, name, years from test_mock_tablesfwse1");
    }

    function testCount()
    {
        $count = soDBTable::count("from test_mock_table");
        $this->assertEquals(3, $count);
        $count = soDBTable::count("from test_mock_table where ID = 10000");
        $this->assertEquals(0, $count);
    }

    function testQuery()
    {
        $count = soDBTable::query("update test_mock_table set years = years + 1 where ID = 1593490");
        $this->assertEquals($count, 1);
        $this->mock->init(1593490, 'ID, name, years');
        $this->assertEquals(31, $this->mock->attr('years'));
        $count = soDBTable::query("delete from test_mock_table");
        $this->assertEquals($count, 3);
    }

    function testInit()
    {
        $this->mock->init(1593490, 'ID, name, years');
        $this->assertEquals(30, $this->mock->attr('years'));
        $this->assertEquals('zhujiyu', $this->mock->attr('name'));
    }

    function testDelete()
    {
        $this->mock->init(1593490, 'ID, name, years');
        $r = $this->mock->delete();
        $this->assertEquals(1, $r);
        $this->assertTablesEqual($this->_getXmlTable('dbtable_test_mock_after_delete.xml'),
                $this->_getDatabaseTable());
    }

    /**
     * @expectedException soException
     */
    function testDeleteException()
    {
        $this->mock->init(1593491, 'ID, name, years');
        $r = $this->mock->delete();
    }

    function testUqdate()
    {
        $this->mock->init(1593490, 'ID, name, years');
        $r = $this->mock->update(array('years'=>33));
        $this->assertEquals(1, $r);
        $this->assertTablesEqual($this->_getXmlTable('dbtable_test_mock_after_update.xml'),
                $this->_getDatabaseTable());
    }

    /**
     * @expectedException soParamException
     */
    function testUqdateParamException()
    {
        $this->mock->init(1593490, 'ID, name, years');
        $r = $this->mock->update('years');
    }

    /**
     * @expectedException soDBException
     */
    function testUqdateDBException()
    {
        $this->mock->init(1593490, 'ID, name, years');
        $r = $this->mock->update(array('year'=>33));
    }

    function testInsertNewData()
    {
        $this->mock->add_new_data(array('name'=>'新加帐号', 'years'=>25));
        $this->assertTablesEqual($this->_getXmlTable('dbtable_test_mock_after_add.xml'),
                $this->_getDatabaseTable());
    }

    function testInsertWithID()
    {
        $r = $this->mock->add_new_data(array('ID'=>23232, 'name'=>'insertuser', 'years'=>22));
        $this->assertTrue(is_a($r, 'soDBTable'));
        $this->assertEquals('insertuser', $this->mock->attr('name'));

        $this->assertTablesEqual($this->_getXmlTable('dbtable_test_mock_after_insert.xml'),
                $this->_getDatabaseTable());
    }

    function testCheckRow()
    {
        $r = $this->mock->check_row_exist(array('ID'=>23232));
        $this->assertTrue(!$r);
        $r = $this->mock->check_row_exist(array('name'=>'zhujiyu'));
        $this->assertTrue($r);
    }

    /**
     * @expectedException PDOException
     */
    function testCheckRowPDOException()
    {
        $r = $this->mock->check_row_exist(array('namesew'=>'zhujiyu'));
        $this->assertTrue($r);
    }
}
?>

<?php
/**
 * @package: DIS.INSTALL
 * @file   : DisInstall.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once dirname(__FILE__)."/../../common.inc.php";

class DisInstall extends DisDataBaseTest
{
    function  __construct()
    {
        DisDBTable::$readPDO  = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');
        DisDBTable::$writePDO = new PDO('mysql:host=localhost;dbname=dinstr', 'jiyu', 'jiyu');

        $sqls = array();
        $this->default_data_file = "install.xml";
        parent::__construct($sqls);
    }

    protected function getDataSet()
    {
        return $this->_getDataSet($this->default_data_file, dirname(__FILE__)."/");
    }

    function testInstallChannels()
    {
        $chan = new DisChannelCtrl(20000);
        $this->assertEquals('这副海报是新的 高战 ', $chan->attr('desc'));
        $user = new DisUserCtrl(10000);
        $this->assertEquals('海报栏01号用户', $user->attr('sign'));
    }
}
?>

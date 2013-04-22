<?php
/**
 * @package: PMAIL.DB
 * @file   :  pmDBTable
 * @abstract  : 数据库表/视图基类
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

abstract class DisDBTable extends DisObject
{
    public $ID;
    protected $table;
    public static $readPDO = null;
    public static $writePDO = null;

    // 构造函数
    function __construct($id = 0)
    {
        $this->ID = 0;
        parent::__construct();
        if( (int)$id > 0 && is_integer($id) )
        {
            $this->init((int)$id);
//            $this->ID = (int)$id;
        }
    }

    protected function _strip_tags() { }

    // 以自增ID列为主键的数据行的标准加载方式
    function init($obj, $slt = '*')
    {
        if( !$obj )
            throw new DisParamException("必须传入有效的主键值。$obj");

        if( is_object($obj) && is_a($obj, 'pmDBTable') )
        {
            $this->ID = (int)$obj->ID;
            $this->detail = $obj->detail;
            return $this;
        }
        else if( !is_integer($obj) && !is_string($obj) )
            return null;

        $this->select("ID = $obj", $slt);
        return $this;
    }

    // 加载新对象
    function select($whr, $slt = "*")
    {
        if( !$whr )
            throw new DisParamException('必须传入查询条件语句');

        $str = "select $slt from $this->table where $whr";
        $data = self::load_line_data($str);

        if( $data )
        {
            $this->ID = (int)$data['ID'];
            $this->detail = $data;
            $this->_strip_tags($this->detail);
        }
        else
            $this->ID = 0;

        return $this;
    }

    protected function _check_param($name, $value)
    {
        return 0;
    }

    function row_exist($info)
    {
        if( !is_array($info) || count($info) == 0 )
            throw new DisParamException('参数不是数组类型');

        $prms = '';
        foreach ($info as $name=>$value)
        {
            if( $value != null && $value != ''
                && $this->_check_param($name, $value) != PMAIL_SUCCEEDED )
                throw new DisParamException("参数 $name 的格式不正确");
            $prms .= "$name = '$value' and ";
        }

//        $prms = substr($prms, 0, strlen($prms) - 5);
        $str = "from $this->table where ".substr($prms, 0, strlen($prms) - 5);
        return self::count($str) == 1;
    }

    /**
     * 插入一条新对象
     * @param array $info 对象信息数组
     * @return DisDBTable 返回插入的对象
     */
    protected function insert($info, PDO $pdo = null)
    {
        if( !is_array($info) || !count($info) )
            throw new DisParamException('参数不是数组类型');
        if( $pdo == null )
            $pdo = DisDBTable::$writePDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');

        $prms = $vlus = "";
        foreach ($info as $name=>$value)
        {
            if( $value != null && $value != ''
                && $this->_check_param($name, $value) != PMAIL_SUCCEEDED )
                throw new DisParamException("参数 $name 的格式不正确, $value");

            if( $value != null && $value != '' )
            {
                $prms .= "`$name`, ";
                $value = preg_replace('/\"/', '\"', $value);
                $vlus .= "\"".$value."\", ";
            }
        }

        $prms = substr($prms, 0, strlen($prms) - 2);
        $vlus = substr($vlus, 0, strlen($vlus) - 2);
        $str = "insert into $this->table ($prms) values ($vlus)";

//echo $str;
//echo '<br>';

        $count = $pdo->exec($str);
        if( $count == false || $count != 1 )
            throw new DisDBException('执行失败！');

        $ID = $pdo->lastInsertId();
        $this->init($ID);
        return $this;
    }

    // 修改当前的对象
    function update($info, PDO $pdo = null)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化');
        if( !is_array($info) || !count($info) )
            throw new DisParamException('参数不是数组类型');
        if( $pdo == null )
            $pdo = DisDBTable::$writePDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');

        $setting = '';
        $count = 0;
        $whr = " where ID = $this->ID";

        foreach ($info as $name=>$value)
        {
            if( $value != null && $value != '' && $this->_check_param($name, $value) != PMAIL_SUCCEEDED )
                throw new DisParamException("参数 $name 的格式不正确");

            if( $this->detail[$name] != $value )
            {
                $setting .= "`$name` = '$value', ";
            }
        }

        if( strlen($setting) > 2 )
        {
            $setting = substr($setting, 0, strlen($setting) - 2);
            $str = "update $this->table set $setting $whr";

            $count = $pdo->exec($str);
            if( $count == false || $count == 0 )
                throw new DisDBException('数据更新操作失败！');

            foreach ($info as $name=>$value)
            {
                if( $this->detail[$name] != $value )
                    $this->detail[$name] = $value;
            }
        }
        return $count;
    }

    // 删除当前的对象
    function delete(PDO $pdo = null)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化');
        if( $pdo == null )
            $pdo = DisDBTable::$writePDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');

        $str = "delete from $this->table where ID = $this->ID";
        $count = $pdo->exec($str);
        if( $count == 0 )
            throw new DisDBException('执行删除操作失败！');
        return $count;
    }

    /**
     * 读取符合条件的数据行数
     * @param string $frmwhr from和where子句
     * @param PDO $pdo 数据库连接
     * @return integer 满足条件的行数
     */
    static function count($frmwhr, PDO $pdo = null)
    {
        if( !$frmwhr )
            throw new DisParamException('读取数据的SQL语句不能为空');
        if( $pdo == null )
            $pdo = DisDBTable::$readPDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');

        $statement = $pdo->query("select count(1) as size $frmwhr");
        if( $statement == null )
            return 0;
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        return $data['size'];
    }

    /**
     * 加载单行数据，读取不到数据时，返回NULL
     * @param string $str 要执行的SQL语句
     * @param array $strip_tags 剥离html标记的字段列表
     * @param array $params 当要执行的SQL语句含有参数时，参数列表
     * @param PDO $pdo 数据库连接
     * @return array 读取的一行数据，或者NULL
     */
    static function load_line_data($str, $strip_tags = null, $params = null, PDO $pdo = null)
    {
        if( !$str )
            throw new DisParamException('读取数据的SQL语句不能为空');
        if( $params != null && !is_array($params) )
            throw new DisParamException('读取数据的SQL语句不能为空');
        if( $pdo == null )
            $pdo = self::$readPDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');

        if( $params != null )
        {
            $statement = $pdo->prepare($str);
            $statement->execute($params);
        }
        else
            $statement = $pdo->query($str);

        if( $statement == null )
            throw new DisDBException('读取数据失败！');

        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if( $strip_tags && is_array($strip_tags) )
        {
            foreach ( $strip_tags as $key => $name )
                $data[$name] = strip_tags($data[$name]);
        }
        return $data;
    }

    /**
     * 加载数据列表，读取不到数据时，返回NULL
     * @param string $str 读取数据的SQL语句
     * @param array $strip_tags 剥离html标记的字段列表
     * @param array $params 当要执行的SQL语句含有参数时，参数列表
     * @param PDO $pdo 数据库连接
     * @return array 读取的数据列表，或者NULL
     */
    static function load_datas($str, $strip_tags = null, $params = null, PDO $pdo = null)
    {
        if( !$str )
            throw new DisParamException('读取数据的SQL语句不能为空');
        if( $params != null && !is_array($params) )
            throw new DisParamException('读取数据的SQL语句不能为空');
        if( $pdo == null )
            $pdo = DisDBTable::$readPDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');

        if( $params )
        {
            $statement = $pdo->prepare($str);
            $statement->execute($params);
        }
        else
            $statement = $pdo->query($str);

        if( $statement == null )
            throw new DisDBException('读取数据失败！');
        $datas = array();

        $count = $statement->rowCount();
        for ($i = 0; $i < $count; $i ++)
        {
            $datas[$i] = $statement->fetch(PDO::FETCH_ASSOC);
            if( $strip_tags )
            {
                foreach ( $strip_tags as $key => $name )
                {
                    $datas[$i][$name] = strip_tags($datas[$i][$name]);
                }
            }
        }
        return $datas;
    }

    protected function _check_num_param($param)
    {
        return 0;
    }

    function increase($param, $step = 1)
    {
        if( !$this->ID || !$this->table )
            throw new DisParamException('对象没有初始化。');
        if( !$this->_check_num_param($param) )
            throw new DisParamException("无效的参数 $param");

        $r = self::query("update $this->table set $param = $param + $step where ID = $this->ID");
        if( $r != 1 )
            throw new DisDBException('更新参数失败。');
        if( $this->detail[$param] )
            (int)$this->detail[$param] += $step;
        else
            $this->detail[$param] = $step;
        return $r == 1;
    }

    function reduce($param, $step = 1)
    {
        if( !$this->ID || !$this->table )
            throw new DisParamException('对象没有初始化。');
        if( !$this->_check_num_param($param) )
            throw new DisParamException('无效的参数。');

        if( !array_key_exists($param, $this->detail) )
        {
            $r = $this->init($this->ID);
            if( !array_key_exists($param, $this->detail) )
                throw new DisException("无效的参数 $param ");
        }
        $value = max($this->detail[$param] - $step, 0);
        if( $value == $this->detail[$param] )
            return 0;

        $r = self::query("update $this->table set $param = $value
            where ID = $this->ID");
        if( $r != 1 )
            throw new DisDBException('更新参数失败。');
        $this->detail[$param] = $value;
        return $r == 1;
    }

    /**
     * 执行一条写数据库语句
     * @param string SQL语句
     * @param PDO $pdo
     * @return integer 更新的行数
     */
    static function query($str, PDO $pdo = null)
    {
        if( !$str )
            throw new DisParamException('执行的SQL语句不能为空！');
        if( $pdo == null )
            $pdo = DisDBTable::$writePDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');
        return $pdo->exec($str);
    }

    static function last_insert_Id(PDO $pdo = null)
    {
        if( $pdo == null )
            $pdo = DisDBTable::$writePDO;
        if( $pdo == null )
            throw new DisException('没有建立数据库连接');
        return $pdo->lastInsertId();
    }
}

if( !DisDBTable::$readPDO )
    DisDBTable::$readPDO = new DisMysqlAdapter('mysql:host='.DisConfigAttr::$dbread['host'].';dbname='.DisConfigAttr::$dbread['dbname'],
            DisConfigAttr::$dbread['username'], DisConfigAttr::$dbread['password']);
if( !DisDBTable::$writePDO )
    DisDBTable::$writePDO = new DisMysqlAdapter('mysql:host='.DisConfigAttr::$dbwrite['host'].';dbname='.DisConfigAttr::$dbwrite['dbname'],
            DisConfigAttr::$dbwrite['username'], DisConfigAttr::$dbwrite['password']);
?>
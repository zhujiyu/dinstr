<?php
/**
 * @package: DIS.DB
 * @file   : DisMysqlStatement.class.php
 * @abstract  : PHPUnit 测试数据库
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisMysqlStatement
{
    protected $query;
    protected $mysql;
    protected $sql_statment;

    function __construct($_mysql, $_query, $_sql_statment = null)
    {
        $this->mysql = $_mysql;
        $this->query = $_query;
        $this->sql_statment = $_sql_statment;
    }

    function rowCount()
    {
        if( !$this->query || !$this->mysql )
            throw new DisException("数据库操作失败！");
        return $this->mysql->num_rows($this->query);
    }

    function fetch($option = PDO::FETCH_ASSOC)
    {
        if( !$this->query || !$this->mysql )
            throw new DisException("数据库操作失败！");

        if( $option == PDO::FETCH_ASSOC || $option == MYSQL_ASSOC )
        {
            return $this->mysql->fetch_array($this->query, MYSQL_ASSOC);
        }
        else if( $option == PDO::FETCH_BOTH || $option == MYSQL_BOTH )
        {
            $result = $this->mysql->fetch_array($this->query, MYSQL_BOTH);
        }
        else if( $option == PDO::FETCH_NUM || $option == MYSQL_NUM )
        {
            $result = $this->mysql->fetch_array($this->query, MYSQL_NUM);
        }
        else
            throw new DisException("错误的参数值！");
        return null;
    }

    function execute ($bound_input_params = null)
    {
        if( !$this->mysql )
            throw new DisException("数据库操作失败！");

        $_sql = $this->sql_statment;

        $c = count($bound_input_params);
        for( $i = 0; $i < $c && preg_match('/\?/', $_sql) > 0; $i ++ )
        {
            $_sql = preg_replace('/\?/', $bound_input_params[$i], $_sql, 1);
        }

        $this->mysql->query($_sql);
        $this->query = $this->mysql->sqlid;
    }
}
?>
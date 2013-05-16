<?php
/**
 * @package: DIS.CORE
 * @file   : DisObject.class.php
 *
 * DIS项目 PHP文件 v1.0.0
 * 有向信息流(Directed Information Stream, DIS)项目的基类
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisObject
{
    protected $detail = null;
    protected static $counts = 0;

    function __construct()
    {
        self::$counts ++;
    }

    static function get_object_ounts()
    {
        return self::$counts;
    }

    function __call($function, $args)
    {
        $c = get_called_class();
        throw new DisException("$c 类成员的方法<b> $function( $args ) </b>不存在！");
    }

    public static function __callStatic($function, $args)
    {
        $c = get_called_class();
        throw new DisException("$c 类的静态方法 $function ( $args ) 不存在或不可见！");
    }

    function __get($attri)
    {
        throw new DisException("读取属性 $attri 不存在或不可见！");
    }

    function __set($attri, $value)
    {
        throw new DisException("属性 $attri 不存在或不可见！无法设置 $value.");
    }

    /**
     * 读取或者设置详细信息
     * @param string $name 信息的键
     * @param object $value 要设置的值，NULL表示读取信息
     * @return object 相应字段的值
     */
    function attr($name, $value = null)
    {
        if( $value == null )
        {
            if( $this->detail != null && array_key_exists($name, $this->detail) )
                $value = $this->detail[$name];
            else
                throw new DisParamException('Not exist key.'.$name);
        }
        else
            $this->detail[$name] = $value;

        return $value;
    }

    function toString()
    {
        return print_r($this, true);
    }

    function info()
    {
        return $this->detail;
    }

    /**
     * @brief 检测数据是否存在
     * @param string $name
     * @return bool true:存在; false:不存在;
     */
    public function __isset($name)
    {
        if(isset($this->detail[$name]))
            return true;
        else
            return false;
    }

    /**
     * @brief 卸载数据
     * @param string $name
     * @return bool true:卸载成功; false:卸载失败;
     */
    public function __unset($name)
    {
        if(isset($this->detail[$name]))
        {
            unset($this->detail[$name]);
            return true;
        }
        else
            return false;
    }

    static function print_array($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    static function rel_array($arr)
    {
        ob_start();
        self::print_array($arr);
        $err = ob_get_contents();
        ob_end_clean();
        return $err;
    }
}
?>
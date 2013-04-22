<?php
/**
 * @package: DIS.CORE
 * @file   : DisTest.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisTest extends DisObject
{
    // 比较两个值是否一样
    static function compare_args($arg1, $arg2)
    {
        if( $arg1 == $arg2 )
            echo "<font color='#ee00ca'>比较</font>： ".self::_toString($arg1)." == ".self::_toString($arg2)." ————<b><font color=green>成功！</font></b><br></p>";
        else
            echo "哈哈<font color='#ee00ca'>比较</font>： ".self::_toString($arg1)." == ".self::_toString($arg2)." ————<b><font color=red>失败！</font></b><br></p>";
    }

    static function _arg_to_string($args)
    {
        if( is_array($args) )
            return print_r($args, true);
        else if( is_string($args) )
            return '"'.$args.'"';
        else if( is_object($args) )
            return print_r($args, true);
        else
            return $args;
    }

    static function arg_to_string($args)
    {
        if( is_array($args) )
        {
            $str = '';
            $c = count($args);
            if ( $c )
                $str = self::_arg_to_string($args[0]);
            for ( $i = 1; $i < $c; $i ++ )
                $str .= ", ".self::_arg_to_string($args[$i]);
            return $str;
        }
        else if ( is_string($args) )
            return '"'.$args.'"';
        else if ( is_object($args) )
            return print_r($args, true);//$args->toString();
        else
            return $args;
    }

    // 将方法名（或者对象和方法名的数组），参数（多个参数时，使用数组）和结果传入。
    static function test_func($func, $args, $value)
    {
        if ( is_array($func) )
        {
            if ( is_object($func[0]) )
                $str = $func[0]->toString().'->';
            else
                $str = $func[0].'::';
            $str .= "<font color=Red><b>".$func[1]."</b></font> ( ";
        }
        else
            $str = "$func ( ";

        $str .= self::arg_to_string($args);
        if ( is_array($args) )
            $v = call_user_func_array($func, $args);
        else
            $v = call_user_func($func, $args);
        $str .= " ) 结果为： ".$v;

        if ( $v == $value )
        {
            echo "<p><font color='#ee00ca'>测试</font> $str   ————<b><font color=green>成功！</font></b><br></p>";
            return err(PMAIL_SUCCEEDED, "测试 $str 成功！");
        }
        else
        {
            echo "<p><font color='#ee00ca'>测试</font> $str   ————<b><font color=red>失败！".getErrInfo()."。</font></b><br></p>";
            return err(PMAIL_ERR_CUSTOM, "测试 $str 失败！");
        }
    }
}
?>
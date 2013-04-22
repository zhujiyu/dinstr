<?php
// 用户测试模块
class Test
{
    // 比较两个值是否一样
    static function CompareArg($arg1, $arg2)
    {
        if( $arg1 == $arg2 )
            echo "<font color='#ee00ca'>比较</font>： ".self::_toString($arg1)." == ".self::_toString($arg2)." ————<b><font color=green>成功！</font></b><br></p>";
        else
            echo "哈哈<font color='#ee00ca'>比较</font>： ".self::_toString($arg1)." == ".self::_toString($arg2)." ————<b><font color=red>失败！</font></b><br></p>";
    }
    
    static function _toString($args)
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
    
    static function toString($args)
    {
        if( is_array($args) )
        {
            $str = '';
            $c = count($args);
            if ( $c ) 
                $str = self::_toString($args[0]);
            for ( $i = 1; $i < $c; $i ++ )
                $str .= ", ".self::_toString($args[$i]);
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
    static function TestFunc($func, $args, $value)
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
        
        $str .= self::toString($args);
        if ( is_array($args) )
            $v = call_user_func_array($func, $args);
        else
            $v = call_user_func($func, $args);
        $str .= " ) 结果为： ".$v;
        
        if ( $v == $value )
        {
            echo "<p><font color='#ee00ca'>测试</font> $str   ————<b><font color=green>成功！</font></b><br></p>";
            return err(XHS_SUCCEEDED, "测试 $str 成功！");
        }
        else
        {
            echo "<p><font color='#ee00ca'>测试</font> $str   ————<b><font color=red>失败！".getErrInfo()."。</font></b><br></p>";
            return err(XHS_ERR_CUSTOM, "测试 $str 失败！");
        }
    }
}
?>

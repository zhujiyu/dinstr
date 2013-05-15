<?php
/**
 * @package: DIS.CORE
 * @file   : DisException.class.php
 * @abstract  : 异常处理
 *
 * 有向信息流(Directed Information Stream, DIS)项目 PHP文件 v1.0.0
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisException extends Exception
{
    function  __construct($mess)
    {
        $this->message = $mess;
    }

    function trace_stack()
    {
        $rlt = "<h3>".$this->getMessage()."</h3>";
        $rlt .= "<pre class='content'>".$this->xdebug_message.'</pre>';
        echo $rlt;
    }

    static function format($str, $len)
    {
        $rlt = $str;
        $len -= strlen($str);
        for( $j = 0; $j < $len; $j ++ )
            $rlt .= " ";
        return $rlt;
    }

    function trace_stack0()
    {
        $rlt = "<h3>".$this->getMessage()."</h3>";
        $rlt .= "<pre>调用堆栈：\n";
        $rlt .= self::format($this->getFile(), 50);
        $rlt .= self::format($this->getLine(), 10);
        $rlt .= "\n";

        $trace = $this->getTrace();
        $count = count($trace);

        for( $i = 0; $i < $count; $i ++ )
        {
            if( !$trace[$i]['file'] )
                continue;
            $rlt .= self::format($trace[$i]['file'], 50);
            $rlt .= self::format($trace[$i]['line'], 10);
            $rlt .= "\n";
        }

        $rlt .= "</pre>";
        echo $rlt;
        return;
    }

    function trace_stack1()
    {
        $rlt = "<pre><h3>".$this->getMessage()."</h3>";
        $rlt .= "<pre class='content'>".$this->getFile()."\t".$this->getLine()."<br>";

        $trace = $this->getTrace();
        $count = count($trace);

        if( $count > 0 )
            $rlt .= "调用堆栈：\n"
                ."+-------------------------------------------------+---------+-------------------+-------------------+\n";

        for( $i = 0; $i < $count; $i ++ )
        {
            if( !$trace[$i]['file'] )
                continue;

            $rlt .= self::format("| ".$trace[$i]['file'], 50);
            $rlt .= self::format("| ".$trace[$i]['line'], 10);
            $rlt .= self::format("| ".$trace[$i]['class'], 20);
            $rlt .= self::format("| ".$trace[$i]['function'], 20);

            $rlt .= "|\n"
                ."+-------------------------------------------------+---------+-------------------+-------------------+\n";
        }

        $rlt .= "</pre>";
        echo $rlt;
    }
}

class DisParamException extends DisException
{
    function  __construct($mess)
    {
        $this->message = $mess;
    }
}

class DisDBException extends DisException
{
    function  __construct($mess)
    {
        $this->message = $mess;
    }
}

class DisPWException extends DisException
{
    function  __construct($mess)
    {
        $this->message = $mess;
    }
}
?>
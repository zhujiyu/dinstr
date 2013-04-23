<?php
/**
 * @file : common.inc.php
 *
 * DIS(有向信息流) 公共头文件
 * 此文件设置和运行环境相关的系统配置
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
//header('Content-Type:text/html;charset=gb2312');
header('Content-Type:text/html; charset=utf-8');
//error_reporting(7);
date_default_timezone_set("PRC");

// 错误代码，简码
define('DIS_SUCCEEDED',    0);
define('DIS_ERR_TIMEOUT', -1);
define('DIS_ERR_CUSTOM',  -2);
define('DIS_ERR_MYSQL',   -3);
define('DIS_ERR_NOINIT',  -4);
define('DIS_ERR_PARAM',   -5);
define('DIS_ERR_STRFMT',  -6);
define('DIS_ERR_EXIST',   -7);
define('DIS_ERR_DENIED',  -8);
define('DIS_ERR_BADDATA', -9);
define('DIS_ERR_OTHER',  -10);
define('IN_DIS', TRUE);
define('DIS_ROOT', dirname(__FILE__).'/');

require_once(DIS_ROOT.'templates/libs/Smarty.class.php');
require_once(DIS_ROOT.'include/core/common.func.php');
require_once(DIS_ROOT.'include/DisConfigAttr.class.php');

function __autoload($class_name)
{
    try
    {
        if( !isset(DisConfigAttr::$autoLoad) || !isset(DisConfigAttr::$autoLoad[$class_name]) )
            throw new DisException("找不到类 $class_name 的定义。");
        include_once DisConfigAttr::$autoLoad[$class_name];
//        echo $class_name."<br>";
//        echo DisConfigAttr::$autoLoad[$class_name]."<br>";
    }
    catch (DisException $ex)
    {
        $ex->trace_stack();
    }
}

function init_smarty()
{
    $gSmarty = new Smarty;
    $gSmarty->template_dir = DIS_ROOT.'templates/';
    $gSmarty->config_dir   = DIS_ROOT.'configs/';
    $gSmarty->cache_dir    = DIS_ROOT.'cache/';
    $gSmarty->compile_dir  = DIS_ROOT.'templates_c/';
//    $gSmarty->compile_check = true;

    $gSmarty->assign("app", DisConfigAttr::$app);
    $gSmarty->assign("comp", DisConfigAttr::$comp);
    return $gSmarty;
}

session_start();
?>
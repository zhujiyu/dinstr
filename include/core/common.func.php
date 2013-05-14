<?php
/**
 * @package: DIS.CORE
 * @file   : common.func.php
 *
 * DIS项目 PHP文件 v1.0.0
 * @abstract  : 一些常用函数
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

$errNo = 0; $errInfo = '';

function uid_check($uid)
{
    $reg = '/^[1-9]\d{2,9}$/';
    return preg_match($reg, $uid);
}

function email_check($email)
{
    $reg = '/^(\w+[-|\.]?)+\w@(\w+\.)+[a-z]{2,}$/i';
    return preg_match($reg, $email);
}

function name_check($username)
{
    $reg = '/^[\w\x{4e00}-\x{9fa5}]+$/u';
    return preg_match($reg, $username);
}

function nick_check($nick)
{
    $reg = '/^[\w\x{4e00}-\x{9fa5}]+$/u';
    return preg_match($reg, $nick);
}

function domain_check($domain)
{
    $reg = '/^\w+$/i';
    return preg_match($reg, $domain);
}

function password_check($password)
{
    $reg = '/^\w{6,}$/i';
    return preg_match($reg, $password);
}

function telephone_check($telephone)
{
    //$reg = '/^1[358]\d{9}$/';
    $reg = '/^(1[358]\d{9}, *)*1[358]\d{9}$/';
    return preg_match($reg, $telephone);
}

function phone_check($phone)
{
    $reg = '/^(0\d{2,3}-\d{7,8}, *)*0\d{2,3}-\d{7,8}$/';
    return preg_match($reg, $phone);
}

function zipcode_check($zip)
{
    return preg_match('/^[1-9]\d{5}$/', $zip);
}

function chunk_array($list, $type)
{
    $val = array();
    $len = count($list);
    for ( $i = 0; $i < $len; $i ++ )
        $val[$i] = $list[$i][$type];
    return $val;
}

/**
 * 折半查找，找到升序数组中不大于要查找值的最大的索引值
 * @param array $list 要查找的数组 升序数组
 * @param integer $val 查找的值
 * @param integer $min 数组的最小索引
 * @param integer $max 数组的最大索引
 * @return integer 返回查找到的不大于要查找值的最大的索引值，
 *                   或者-1，表示所有值都大于要查找的值
 */
function _asc_bin_search($list, $val, $min, $max)
{
    if( $list[$min] > $val )
        return -1;
    else if( $list[$min] == $val )
        return $min;
    else if( $list[$max] <= $val )
        return $max;

    while ( $min < $max )
    {
        $mid = (int)(($min + $max) / 2);
        if( $mid == $min )
            return $min;
        else if( $val < $list[$mid] )
            $max = $mid;
        else if( $val > $list[$mid] )
            $min = $mid;
        else
            return $mid;
    }
    return $min;
}

/**
 * 折半查找，找到降序数组中不小于要查找值的最大的索引值
 * @param array $list 要查找的数组 降序数组
 * @param integer $val 查找的值
 * @param integer $min 数组的最小索引
 * @param integer $max 数组的最大索引
 * @return integer 返回查找到的不大于要查找值的最大的索引值，
 *                   或者-1，表示所有值都大于要查找的值
 */
function _desc_bin_search($list, $val, $min, $max)
{
    if( $val > $list[$min] )
        return -1;
    else if( $val == $list[$min] )
        return $min;
    else if( $val <= $list[$max] )
        return $max;

    while ( $min < $max )
    {
        $mid = (int)(($min + $max) / 2);
        if( $mid == $min )
            return $min;
        else if( $val > $list[$mid] )
            $max = $mid;
        else if( $val < $list[$mid] )
            $min = $mid;
        else
            return $mid;
    }
    return $min;
}

/**
 * 折半查找有序数组
 * @param array $list 数字数组
 * @param integer $val 要查找的值
 * @param string $rank 数组排序方式
 * @return integer 返回查找的值在数组中的下标，或者 -1
 */
function bin_search($list, $val, $rank = 'asc')
{
    $min = 0;
    $max = count($list) - 1;

    if( $rank == 'asc' )
        $idx = _asc_bin_search($list, $val, $min, $max);
    else
        $idx = _desc_bin_search($list, $val, $min, $max);

    if( $idx >= 0 && $list[$idx] != $val )
        return -1;
    return $idx;
}

/**
 * 值是否存在于有序数组中
 * @param array $list 数字数组
 * @param integer $val 要查找的值
 * @param string $rank 数组排序方式
 * @return boolen 要查的值在数组中返回true，否则返回false
 */
function in_rank_array($val, $list, $rank = 'asc')
{
    $min = 0;
    $max = count($list) - 1;

    if( $rank == 'asc' )
        $idx = _asc_bin_search($list, $val, $min, $max);
    else
        $idx = _desc_bin_search($list, $val, $min, $max);

    if( $idx < 0 )
        return false;
    else if ( $list[$idx] != $val )
        return false;
    else
        return true;
}

function list_slice($list, $start, $count)
{
    $have = count($list);
    if( $list[0] == "#E#" || $have < $start )
        $_list = array();
    else if( $have < $start + $count )
        $_list = array_slice($list, $start);
    else
        $_list = array_slice($list, $start, $count);
    return $_list;
}

function keyword_parse($keyword)
{
    $reg = '/[\w\x{4e00}-\x{9fa5}]+/u';
    $matches = array();
    if( preg_match_all($reg, $keyword, $matches) )
        return $matches[0];
    else
        return null;
}

function reg_uid_list($uidlist)
{
    $rsg = '/^([1-9]\d{2,}, *)*[1-9]\d{2,}$/';
    if( $uidlist == 'everyone' )
        return 1;
    return preg_match($rsg, $uidlist);
}

function get_first_uid($uidlist)
{
    $rsg = '/^[1-9][0-9]{2,}/';
    $matches = array();
    if( preg_match($rsg, $uidlist, $matches) )
        return $matches[0];
    return null;
}

function reg_name_list($unamelist)
{
    $rsglist = '/^([\w\x{4e00}-\x{9fa5}]+,\s*)*[\w\x{4e00}-\x{9fa5}]+$/u';
    return preg_match($rsglist, $unamelist);
}

function get_first_name($unamelist)
{
    $rsgsingle = '/^[\w\x{4e00}-\x{9fa5}]+/u';
    $matches = array();
    if( preg_match($rsgsingle, $unamelist, $matches) )
        return $matches[0];
    return null;
}

function get_name_list($unamelist, $atnames = null)
{
    if( $atnames && !is_array($atnames) )
        $atnames = array($atnames);
    $rsgsingle = '/[@＠]([\w\x{4e00}-\x{9fa5}]+)/u';
    $matches = array();
    if( !preg_match_all($rsgsingle, $unamelist, $matches) )
        return $atnames;

    if( $atnames )
        $atnames = array_merge($atnames, $matches[1]);
    else
        $atnames = $matches[1];
    $atnames = array_unique($atnames);
    return $atnames;
}

function replace_name_list($unamelist)
{
    $rsgsingle = '/[\w\x{4e00}-\x{9fa5}]+/u';
    return preg_replace($rsgsingle, '\'$0\'', $unamelist);
}

function pmail_array_delete(&$arr, $var)
{
    $index = array_search($var, $arr);
    return array_splice($arr, $index, 1);
}

function err($err, $info = '')
{
    global $errNo, $errInfo;
    $errInfo = $info;
    $errNo = $err;

    if ( !$info )
    {
        switch($err)
        {
            case DIS_ERR_TIMEOUT: $errInfo = "操作超时！"; break;
            case DIS_ERR_CUSTOM: $errInfo = "用户定义错误！"; break;
            case DIS_ERR_MYSQL: $errInfo = "MYSQL操作错误！"; break;
            case DIS_ERR_NOINIT: $errInfo = "对象没有初始化！"; break;
            case DIS_ERR_PARAM: $errInfo = "传入的参数不正确！"; break;
            case DIS_ERR_STRFMT: $errInfo = "字符串格式错误！"; break;
            case DIS_ERR_EXIST: $errInfo = "对象存在性错误！"; break;
            case DIS_ERR_DENIED: $errInfo = "权限不足！"; break;
            case DIS_ERR_OTHER: $errInfo = "其他错误！"; break;
            case DIS_SUCCEEDED: $errInfo = "操作成功！"; break;
            default: break;
        }
    }

    return $err;
}

function get_err_info()
{
    global $errInfo;
    return $errInfo;
}

function _index($variable, $arr)
{
    if( !$variable || !$arr || !is_array($arr) )
        return err(DIS_ERR_PARAM);
    foreach ($arr as $name=>$value)
	{
	    if( $variable == $value )
            return $name;
    }
    return -1;
}
?>
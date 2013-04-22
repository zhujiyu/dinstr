<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');
/**
 * Smarty date_ago modifier plugin
 *
 * Type:     modifier
 *
 * Name:     date_ago
 
 * Purpose:  通过时间戳获取时间戳离现在多久
 * @author   StMadMan
 * @param timestamp|string
 * @param dateformat|string
 * @return string|void
 */

function smarty_modifier_date_ago($string, $dateformat = 'm月d日 H:i')
{
    if( $string != '' )
        $timestamp = smarty_make_timestamp($string);
    else
        return;
    
    $now = time();
    if( $timestamp > $now )
        return;
    
    $agoTime = $now - $timestamp;
    $today = mktime(0, 0, 0);
    
    if( $agoTime <= 60 ) 
        return $agoTime.'秒前';
    else if( $agoTime <= 3600 && $agoTime > 60 )
        return intval($agoTime / 60) .'分钟前';
    else if( $timestamp > $today )
        return "今天 ".date('H:i', $timestamp);
    else
        return date($dateformat, $timestamp);
}
?>

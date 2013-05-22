<?php
/**
 * @package: DIS.PAGE
 * @file   : test.php
 * 个人首页
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS(有向信息流)
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
require_once 'common.inc.php';

$a = array('6685', '325', '54645');
$b = array('12', '325', '554', '6685', '46574', '54645');

$len1 = count($a);
$len2 = count($b);

        for( $i = 0; $i < $len1; $i ++ )
        {
            $len2 = count($b);
            for( $j = 0; $j < $len2; $j ++ )
            {
                if( $b[$j] == $a[$i] )
                {
                    array_splice($b, $j, 1);
                    break;
                }
            }
        }

//$matches = null;
//$notices = "dasfjoaiwetu464567e98";
//preg_match_all('/\d+/', $notices, $matches);
DisObject::print_array($a);
DisObject::print_array($b);
?>
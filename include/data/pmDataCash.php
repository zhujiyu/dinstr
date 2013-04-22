<?php
/**
 * @package: PMAIL.DATA
 * @file  : pmDataCash.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class pmDataCash extends DisObject
{
    static function insert($user, $club, $money)
    {
        $str = "insert into cashes (user_id, club_id, money) values ($user, $club, $money)";
        return dbObject::query($str);
    }

    static function cashes(&$cashes, $club)
    {
        $str = "select ID, user_id, club_id, money, cash_time
            from cashes where club_id = $club";
        return dbObject::datas($recharges, $str);
    }
}
?>
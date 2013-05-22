<?php
/**
 * @file :  DisNoteDataCache.class.php
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisNoteDataCache extends DisRowCache
{
    static function get($key)
    {
        return parent::get('mail-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('mail-'.$key, $value);
    }

    static function get_approve_data($approve_id)
    {
        $key = "as-$approve_id";
        return self::get($key);
    }

    static function set_approve_data($approve_id, $approve)
    {
        $key = "ns-$approve_id";
        self::set($key, $approve);
    }

    static function get_flow_note($id)
    {
        $key = "fn-$id";
        return self::get($key);
    }

    static function set_flow_note($id, $note)
    {
        $key = "fn-$id";
        self::set($key, $note);
    }

    static function get_head_data($theme_id)
    {
        $key = "wdd-$theme_id";
        return self::get($key);
    }

    static function set_head_data($theme_id, $theme_data)
    {
        $key = "wdd-$theme_id";
        self::set($key, $theme_data);
    }

    static function get_note_data($mail_id)
    {
        $key = "ndd-".$mail_id;
        return self::get($key);
    }

    static function set_note_data($mail_id, $data)
    {
        $key = "ndd-".$mail_id;
        self::set($key, $data);
    }
}
?>

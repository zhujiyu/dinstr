<?php
/**
 * Description of pmCacheUser
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisUserDataCache extends DisRowCache
{
    static function get($key)
    {
        return parent::get('user-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('user-'.$key, $value);
    }

    static function get_user_data($user_id)
    {
        $key = "udd-".$user_id;
        return self::get($key);
    }

    static function set_user_data($user_id, $data)
    {
        $key = "udd-".$user_id;
        self::set($key, $data);
    }

    static function get_user_param($user_id)
    {
        $key = "ups-".$user_id;
        return self::get($key);
    }

    static function set_user_param($user_id, $param)
    {
        $key = "ups-".$user_id;
        self::set($key, $param);
    }

    static function get_relation_data($user_id)
    {
        $key = "rd-".$user_id;
        return self::get($key);
    }

    static function set_relation_data($user_id, $data)
    {
        $key = "rd-".$user_id;
        self::set($key, $data);
    }

    static function get_uid_by_name($name)
    {
        $key = "uidn-".$name;
        return self::get($key);
    }

    static function set_uid_by_name($name, $user_id)
    {
        $key = "uidn-".$name;
        self::set($key, $user_id);
    }

    static function get_uid_by_email($email)
    {
        $key = "uidd-".$email;
        return self::get($key);
    }

    static function set_uid_by_email($email, $user_id)
    {
        $key = "uidd-".$email;
        self::set($key, $user_id);
    }

    static function get_login_id($user_id)
    {
        $key = "login-".$user_id;
        return self::get($key);
    }

    static function set_login_id($user_id, $login_id)
    {
        $key = "login-".$user_id;
        self::set($key, $login_id);
    }

    static function get_last_inline($user_id)
    {
        $key = "inline-time-".$user_id;
        return self::get($key);
    }

    static function set_last_inline($user_id, $inline_time = 0)
    {
        if( $inline_time == 0 )
            $inline_time = time();
        $key = "inline-time-".$user_id;
        self::set($key, $inline_time);
    }

    static function get_user_logs($user_id)
    {
        $key = "ulogs-$user_id";
        return self::get($key);
    }

    static function set_user_logs($user_id, $logs)
    {
        $key = "ulogs-$user_id";
        self::set($key, $logs);
    }

}
?>
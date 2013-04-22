<?php
/**
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisNoteVectorCache extends DisVectorCache
{
    static function get($key)
    {
        return parent::get('mail-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('mail-'.$key, $value);
    }

    static function get_theme_approval_ids($theme_id)
    {
        $key = "waids-$theme_id";
        return self::get($key);
    }

    static function set_theme_approval_ids($theme_id, $user_ids)
    {
        $key = "waids-$theme_id";
        self::set($key, $user_ids);
    }

    static function get_theme_interest_ids($theme_id)
    {
        $key = "tiids-$theme_id";
        return self::get($key);
    }

    static function set_theme_interest_ids($theme_id, $user_ids)
    {
        $key = "tiids-$theme_id";
        self::set($key, $user_ids);
    }

    static function get_mail_ids($theme_id)
    {
        $key = 'tmid-'.$theme_id;
        return self::get($key);
    }

    static function set_mail_ids($theme_id, $mail_ids)
    {
        $key = 'tmid-'.$theme_id;
        self::set($key, $mail_ids);
    }

    static function get_parent_mail_ids($mail_id)
    {
        $key = 'pnids-'.$mail_id;
        return parent::get($key);
    }

    static function set_parent_mail_ids($mail_id, $parent_mail_ids)
    {
        $key = 'pnids-'.$mail_id;
        parent::set($key, $parent_mail_ids);
    }

    static function get_child_mail_ids($mail_id)
    {
        $key = 'cnids-'.$mail_id;
        return self::get($key);
    }

    static function set_child_mail_ids($mail_id, $child_mail_ids)
    {
        $key = 'cnids-'.$mail_id;
        self::set($key, $child_mail_ids);
    }

    /**
     * 获取某个话题最新的一条评论
     * @param int $theme_id 话题ＩＤ
     * @return integer 评论ID
     */
    static function get_last_mail_id($theme_id)
    {
        $key = 'wlnid-'.$theme_id;
        return self::get($key);
    }

    static function set_last_mail_id($theme_id, $last_mail_id)
    {
        $key = 'wlnid-'.$theme_id;
        self::set($key, $last_mail_id);
    }
}
?>

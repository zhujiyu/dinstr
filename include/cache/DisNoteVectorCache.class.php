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
        return parent::get('note-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('note-'.$key, $value);
    }

    static function get_head_approval_ids($head_id)
    {
        $key = "waids-$head_id";
        return self::get($key);
    }

    static function set_head_approval_ids($head_id, $user_ids)
    {
        $key = "waids-$head_id";
        self::set($key, $user_ids);
    }

    static function get_head_interest_ids($head_id)
    {
        $key = "tiids-$head_id";
        return self::get($key);
    }

    static function set_head_interest_ids($head_id, $user_ids)
    {
        $key = "tiids-$head_id";
        self::set($key, $user_ids);
    }

    static function get_note_ids($head_id)
    {
        $key = 'tmid-'.$head_id;
        return self::get($key);
    }

    static function set_note_ids($head_id, $note_ids)
    {
        $key = 'tmid-'.$head_id;
        self::set($key, $note_ids);
    }

    static function get_parent_note_ids($note_id)
    {
        $key = 'pnids-'.$note_id;
        return parent::get($key);
    }

    static function set_parent_mail_ids($note_id, $parent_note_ids)
    {
        $key = 'pnids-'.$note_id;
        parent::set($key, $parent_note_ids);
    }

    static function get_child_note_ids($note_id)
    {
        $key = 'cnids-'.$note_id;
        return self::get($key);
    }

    static function set_child_note_ids($note_id, $child_note_ids)
    {
        $key = 'cnids-'.$note_id;
        self::set($key, $child_note_ids);
    }

    /**
     * 获取某个话题最新的一条评论
     * @param int $theme_id 话题ＩＤ
     * @return integer 评论ID
     */
    static function get_last_note_id($head_id)
    {
        $key = 'wlnid-'.$head_id;
        return self::get($key);
    }

    static function set_last_note_id($head_id, $note_id)
    {
        $key = 'wlnid-'.$head_id;
        self::set($key, $note_id);
    }
}
?>

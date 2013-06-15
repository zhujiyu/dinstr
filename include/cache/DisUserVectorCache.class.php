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

class DisUserVectorCache extends DisVectorCache
{
    static function get($key)
    {
        return parent::get('user-'.$key);
    }

    static function set($key, $value)
    {
        return parent::set('user-'.$key, $value);
    }

    static function get_follow_user_ids($user_id)
    {
        $key = 'flwuid-'.$user_id;
        return self::get($key);
    }

    static function set_follow_user_ids($user_id, $follow_ids)
    {
        $key = 'flwuid-'.$user_id;
        self::set($key, $follow_ids);
    }

    static function get_fan_user_ids($user_id)
    {
        $key = 'fanuid-'.$user_id;
        return self::get($key);
    }

    static function set_fan_user_ids($user_id, $fan_ids)
    {
        $key = 'fanuid-'.$user_id;
        self::set($key, $fan_ids);
    }

    static function get_super_user_ids()
    {
        $key = "suids";
        return self::get($key);
    }

    static function set_super_user_ids($user_ids)
    {
        $key = "suids";
        self::set($key, $user_ids);
    }

    static function get_follow_flow_ids($user_id)
    {
        $key = "ffid-$user_id";
        return self::get($key);
    }

    static function set_follow_flow_ids($user_id, $fids)
    {
        $key = "ffid-$user_id";
        self::set($key, $fids);
    }

    static function push_follow_flow_id($user_id, $fid)
    {
        $key = "ffid-$user_id";
        self::push($key, $fid);
    }

    static function drop_follow_flow_id($user_id, $fid)
    {
        $key = "ffid-$user_id";
        self::drop($key, $fid);
    }

    static function get_interest_head_ids($user_id)
    {
        $key = "fwids-$user_id";
        return self::get($key);
    }

    static function set_interest_head_ids($user_id, $theme_ids)
    {
        $key = "fwids-$user_id";
        self::set($key, $theme_ids);
    }

    static function get_approved_head_ids($user_id)
    {
        $key = "awids-$user_id";
        return self::get($key);
    }

    static function set_approved_head_ids($user_id, $theme_ids)
    {
        $key = "awids-$user_id";
        self::set($key, $theme_ids);
    }

    static function get_reply_note_ids($user_id)
    {
        $key = "rmid-$user_id";
        return self::get($key);
    }

    static function set_reply_note_ids($user_id, $mail_ids)
    {
        $key = "rmid-$user_id";
        self::set($key, $mail_ids);
    }

    static function get_publish_head_ids($user_id)
    {
        $key = "phids-$user_id";
        return self::get($key);
    }

    static function set_publish_head_ids($user_id, $head_ids)
    {
        $key = "phids-$user_id";
        self::set($key, $head_ids);
    }

    /**
     * 获取某位用户最新的一条评论
     * @param int $user_id 话题ＩＤ
     * @return integer 评论ID
     */
    static function get_last_note_id($user_id)
    {
        $key = 'lmid-'.$user_id;
        return self::get($key);
    }

    static function set_last_note_id($user_id, $last_id)
    {
        $key = 'lmid-'.$user_id;
        self::set($key, $last_id);
    }

    static function get_new_notice_ids($user_id)
    {
        $key = "nnis-$user_id";
        return self::get($key);
    }

    static function set_new_notice_ids($user_id, $notices)
    {
        $key = "nnis-$user_id";
        self::set($key, $notices);
    }

    static function get_joined_chan_ids($user_id)
    {
        $key = "jcid-".$user_id;
        return self::get($key);
    }

    static function set_joined_chan_ids($user_id, $channel_ids)
    {
        $key = "jcid-".$user_id;
        self::set($key, $channel_ids);
    }

    static function get_subscribed_chan_ids($user_id)
    {
        $key = "scid-".$user_id;
        return self::get($key);
    }

    static function set_subscribed_chan_ids($user_id, $channel_ids)
    {
        $key = "scid-".$user_id;
        self::set($key, $channel_ids);
    }

    static function get_subscribed_chan_asc_ids($user_id)
    {
        $key = "scascid-".$user_id;
        return self::get($key);
    }

    static function set_subscribed_chan_asc_ids($user_id, $channel_ids)
    {
        $key = "scascid-".$user_id;
        self::set($key, $channel_ids);
    }

    static function get_subscribed_chan_weights($user_id)
    {
        $key = "scws-".$user_id;
        return self::get($key);
    }

    static function set_subscribed_chan_weights($user_id, $weights)
    {
        $key = "scws-".$user_id;
        self::set($key, $weights);
    }

    static function get_chan_roles($user_id)
    {
        $key = "crs-".$user_id;
        return self::get($key);
    }

    static function set_chan_roles($user_id, $roles)
    {
        $key = "crs-".$user_id;
        self::set($key, $roles);
    }
}
?>
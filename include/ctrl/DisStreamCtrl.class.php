<?php
/**
 * @package: DIS.CTRL
 * @file   : DisStreamCtrl.class.php
 * @abstract  :
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisStreamCtrl extends DisStreamData
{
    function  __construct($id = 0)
    {
        parent::__construct($id);
    }

    function init($id)
    {
        $this->ID = $id;
        $this->detail = self::get_data($id);
    }

    static function get_flow_view($flow_id)
    {
        $flow = self::get_data($flow_id);
        if( !$flow )
            return null;
        $flow['user'] = DisUserCtrl::get_data($flow['user_id']);
        if( $flow['channel_id'] > 0 )
            $flow['channel'] = DisChannelCtrl::get_data($flow['channel_id']);

        $mail = DisNoteCtrl::get_note_view($flow['mail_id']);
        $mail[content] = strip_tags($mail[content]);
        if( $mail['ID'] > 0 )
            $mail['theme'] = DisHeadCtrl::get_data($mail['theme_id']);
        $mail['flow'] = $flow;
//        $mail['theme'] = $_data;
        return $mail;
    }

    static function list_flows($flow_ids)
    {
        $flow_list = array();
        $len = count($flow_ids);
        for( $i = 0; $i < $len; $i ++ )
        {
            try
            {
                $mail = self::get_flow_view($flow_ids[$i]);
            }
            catch (DisException $ex)
            {
//                $ex->trace_stack();
                continue;
            }
            array_push($flow_list, $mail);
        }
        return $flow_list;
    }

    static function get_data($flow_id)
    {
//        pmRowMemcached::set_flow_note($flow_id, null);
        $note = DisNoteDataCache::get_flow_note($flow_id);
        if( !$note )
        {
            $note = parent::load_flow_note($flow_id);
            DisNoteDataCache::set_flow_note($flow_id, $note);
        }
        return $note;
    }

    function delete($user_id)
    {
        if( $this->ID == 0 )
            throw new DisParamException('对象没有初始化！');
        if( $this->detail == null )
            $this->detail = self::get_data($this->ID);

        $mail = DisNoteCtrl::note($this->detail['mail_id']);
        if( $mail->attr('user_id') != $user_id )
            return;
        $super_ids = DisUserCtrl::list_super_user_ids();
        if( !in_array($user_id, $super_ids) )
            return;

        parent::delete();
        DisNoteDataCache::set_flow_note($this->ID, null);
        $mail->delete();
        DisNoteDataCache::set_note_data($this->detail['mail_id'], null);

        $feed = DisFeedCtrl::read_ctrler($user_id);
        $feed->drop_flow($this->ID);
        DisFeedCtrl::save_ctrler($feed);
    }
}
?>
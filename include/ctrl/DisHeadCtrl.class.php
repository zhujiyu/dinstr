<?php
/**
 * @package: DIS.CTRL
 * @file   : DisHeadCtrl.class.php
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

class DisHeadCtrl extends DisInfoHeadData
{
    public $mail;

    function _chack_init()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
    }

    function increase($param, $step = 1)
    {
        $this->_chack_init();
        parent::increase($param, $step);
        DisNoteDataCache::set_head_data($this->ID, null);
    }

    function reduce($param, $step = 1)
    {
        $this->_chack_init();
        parent::reduce($param, $step);
        DisNoteDataCache::set_head_data($this->ID, null);
    }

    static function get_data($head_id)
    {
//        pmRowMemcached::set_theme_data($theme_id, null);
        $head_data = DisNoteDataCache::get_head_data($head_id);
        if( !$head_data )
        {
            $head = new DisHeadCtrl();
            $head->init($head_id);
            if( !$head->ID )
                throw new DisParamException("对象不存在！$head_id");
            $head_data = $head->info();
            DisNoteDataCache::set_head_data($head_id, $head_data);
        }
        return $head_data;
    }

    function head_view()
    {
        $this->_chack_init( );
        $view = $this->detail;

        $view['note'] = DisNoteCtrl::get_note_view($view['note_id']);
        $view['note']['content'] = strip_tags($view['note']['content']);
        if( $view['chan_id'] > 0 )
            $view['channel'] = DisChannelCtrl::get_data($view['chan_id']);

        return $view;
    }

    static function parse_heads($head_ids)
    {
        $len = count($head_ids);
        $head_list = array();
        for( $i = 0; $i < $len; $i ++ )
        {
            $head = DisHeadCtrl::head($head_ids[$i]);
            $data = $head->head_view();
            $data['last_note'] = $head->last_note();
            array_push($head_list, $data);
        }
        return $head_list;
    }

    protected function check_interest($user_id)
    {
        $user_ids = $this->list_interest_user_ids();
        return in_array($user_id, $user_ids) ? 1 : 0;
    }

    protected function check_approved($user_id)
    {
        $user_ids = $this->list_approved_user_ids();
        return in_array($user_id, $user_ids) ? 1 : 0;
    }

    function check_status($user_id)
    {
        $interest_uids = $this->list_interest_user_ids();
        $status['interest'] = in_array($user_id, $interest_uids) ? 1 : 0;
        $approved_uids = $this->list_approved_user_ids();
        $status['approved'] = in_array($user_id, $approved_uids) ? 1 : 0;
        return $status;
    }

    static function new_head($user_id, $chan_id, $title)
    {
        if( !$user_id || !$title )
            throw new DisParamException("参数不合法！");
//        $rsg = '/#([\w\x{4e00}-\x{9fa5}]+)#/ui';
//        $title = preg_replace($rsg, '', $title);

        $head = new DisHeadCtrl();
        $head->insert($title, 0, (int)$chan_id);
        if( !$head->ID )
            throw new DisDBException("插入信息头失败！");
        $head->interest($user_id);

        $chan = new DisChannelCtrl($chan_id);
        $chan->increase('info_num');

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->increase('head_num');
        return $head;
    }

    static function head($head_id)
    {
        $head = new DisHeadCtrl();
        $head->ID = $head_id;
        $head->detail = self::get_data($head_id);
        return $head;
    }

    function interest($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('没有操作用户！');

        if( $this->check_interest($user_id) )
            return 0;
        $interest_id = DisInfoUserData::insert($this->ID, $user_id);
        if( !$interest_id )
            throw new DisException('发生了意外异常！');

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->increase('interest_num');
        $this->increase ('interest_num');

        $head_ids = DisUserVectorCache::get_interest_head_ids($user_id);
        if( $head_ids == null )
            $head_ids = array($this->ID);
        else
            array_unshift($head_ids, $this->ID);

        DisUserVectorCache::set_interest_head_ids($user_id, $head_ids);
        DisNoteVectorCache::set_head_interest_ids($this->ID, null);
        return $interest_id;
    }

    function cancel_interest($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('没有操作用户！');

        if( !$this->check_interest($user_id) )
            return ;
        DisInfoUserData::remove($this->ID, $user_id);

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->reduce('interest_num');
        $this->reduce ('interest_num');

        DisUserVectorCache::set_interest_head_ids($user_id, null);
        DisUserVectorCache::set_approved_head_ids($user_id, null);
        DisNoteVectorCache::set_head_interest_ids($this->ID, null);
        DisNoteVectorCache::set_head_approval_ids($this->ID, null);
    }

    function approve($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('没有操作用户！');

        if( $this->check_approved($user_id) )
            return ;
        $approve_id = DisInfoUserData::get_interest_id($this->ID, $user_id);
        if( !$approve_id )
            $approve_id = DisInfoUserData::insert($this->ID, $user_id);
        if( !$approve_id )
            throw new DisDBException('发生了意外异常！');
        DisInfoUserData::approve($approve_id);

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->increase('approved_num');
        $this->increase ('approved_num');

        $note = DisNoteCtrl::get_data($this->detail['note_id']);
        if( $note['user_id'] != $user_id )
        {
            $notice = new DisNoticeCtrl($note['user_id']);
            $notice->add_approve_notice($approve_id);
        }

        DisUserVectorCache::set_interest_head_ids($user_id, null);
        DisUserVectorCache::set_approved_head_ids($user_id, null);
        DisNoteVectorCache::set_head_interest_ids($this->ID, null);
        DisNoteVectorCache::set_head_approval_ids($this->ID, null);
    }

    function cancel_approve($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('没有操作用户！');

        if( !$this->check_approved($user_id) )
            return;
        $approve_id = DisInfoUserData::get_interest_id($this->ID, $user_id);
        if( !$approve_id )
            return;
        DisInfoUserData::cancel_approve($approve_id);

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->reduce('approved_num');
        $this->reduce ('approved_num');

        DisUserVectorCache::set_approved_head_ids($user_id, null);
        DisNoteVectorCache::set_head_approval_ids($this->ID, null);
    }

    function list_interest_user_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        //pmVectorMemcached::set_follow_theme_ids($user_id, null);
        $user_ids = DisNoteVectorCache::get_head_interest_ids($this->ID);
        if( !$user_ids )
        {
            $user_ids[0] = "#E#";
            $ids = DisInfoUserData::list_follow_user_ids($this->ID);
            $count = count($ids);
            for( $i = 0; $i < $count; $i ++ )
                $user_ids[$i] = $ids[$i]['user_id'];
            DisNoteVectorCache::set_head_interest_ids($this->ID, $user_ids);
        }

        if( $user_ids[0] == "#E#" )
            return array();
        return $user_ids;
    }

    function list_approved_user_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

//        pmVectorMemcached::set_theme_approval_ids($this->ID, null);
        $user_ids = DisNoteVectorCache::get_head_approval_ids($this->ID);
        if( !$user_ids )
        {
            $user_ids[0] = "#E#";
            $ids = DisInfoUserData::list_approve_user_ids($this->ID);
            $count = count($ids);
            for( $i = 0; $i < $count; $i ++ )
                $user_ids[$i] = $ids[$i]['user_id'];
            DisNoteVectorCache::set_head_approval_ids($this->ID, $user_ids);
        }

        if( $user_ids[0] == "#E#" )
            return array();
        return $user_ids;
    }

    function list_note_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

//        pmVectorMemcached::set_mail_ids($this->ID, null);
        $note_ids = DisNoteVectorCache::get_note_ids($this->ID);
        if( !$note_ids )
        {
            $note_ids[0] = '#E#';
            $notes = DisNoteCtrl::load_head_notes($this->ID, 'ID');
            $count = count($notes);
            for( $i = 0; $i < $count; $i ++ )
                $note_ids[$i] = $notes[$i]['ID'];
            DisNoteVectorCache::set_note_ids($this->ID, $note_ids);
        }

        if( $note_ids[0] == '#E#' )
            $note_ids = array();
        return $note_ids;
    }

    function last_note_id()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        //pmVectorMemcached::set_last_mail_id($root, null);
        $note_id = DisNoteVectorCache::get_last_note_id($this->ID);
        if( !$note_id )
        {
            $lasts = DisNoteCtrl::last_head_note($this->ID);
            if( $lasts )
                $note_id = $lasts['ID'];
            else
                $note_id = $this->detail['note_id'];
            DisNoteVectorCache::set_last_note_id($this->ID, $note_id);
        }

        return $note_id;
    }

    function last_note()
    {
//        $note_id = $this->last_note_id();
        $note = DisNoteCtrl::get_note_view($this->last_note_id());
        $note['content'] = strip_tags($note['content']);
        return $note;
    }

    function list_all_photos()
    {
        $note_ids = $this->list_note_ids();//self::list_mail_ids();
        $len = count($note_ids);
        for( $i = 0, $pk = 0; $i < $len; $i ++ )
        {
            $note = DisNoteCtrl::get_data($note_ids[$i]);
            if( !$note['photo_list'] )
                continue;
            $count = count($note['photo_list']);

            for( $j = 0; $j < $count; $j ++ )
            {
                $photo = $note['photo_list'][$j];
                $photo_info = DisPhotoCtrl::get_data($photo['photo_id']);
                $photo['url'] = $photo_info['url'];
                $photo['user'] = $photo_info['user'];
                $photos[$pk ++] = $photo;
            }
        }
        return $photos;
    }

    function list_all_goods()
    {
        $note_ids = $this->list_note_ids();//self::list_mail_ids();
        $len = count($note_ids);
        for( $i = 0, $pk = 0; $i < $len; $i ++ )
        {
            $note = DisNoteCtrl::get_data($note_ids[$i]);
            if( !$note['good_list'] )
                continue;
            $count = count($note['good_list']);

            for( $j = 0; $j < $count; $j ++ )
            {
                $good = $note['good_list'][$j];
                $goods[$pk ++] = DisGoodCtrl::get_data($good['good_id']);
            }
        }
        return $goods;
    }
}

//    static function list_themes($count = 10)
//    {
//        $themes = parent::list_themes($count);
//        $len = count($themes);
//        for( $i = 0; $i < $len; $i ++ )
//        {
//            $_id = $themes[$i]['channel_id'];
//            if( $_id )
//                $themes[$i]['channel'] = DisChannelCtrl::get_data($_id);
////            array_push($theme_view, $_view);
//        }
//        return $themes;
//    }
//
//        if( !$this->ID )
//            throw new DisParamException('对象没有初始化！');
//        if( !$this->detail )
//            $this->detail = self::get_data($this->ID);

//        if( $view['note_id'] )
//        {
//            $view['note'] = DisNoteCtrl::get_note_view($view['note_id']);
//            $view['note']['content'] = strip_tags($view['note']['content']);
//        }
//        else if( $view['mail']['channel_list'][0] )
//            $view['channel'] = $view['mail']['channel_list'][0];
//        if( $user_id > 0 )
//            $view['status'] = $this->check_status($user_id);

?>
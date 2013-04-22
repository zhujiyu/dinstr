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

class DisHeadCtrl extends DisHeadData
{
    public $mail;

    function increase($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::increase($param, $step);
        DisNoteDataCache::set_theme_data($this->ID, null);
    }

    function reduce($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::reduce($param, $step);
        DisNoteDataCache::set_theme_data($this->ID, null);
    }

    static function get_data($theme_id)
    {
//        pmRowMemcached::set_theme_data($theme_id, null);
        $theme_data = DisNoteDataCache::get_theme_data($theme_id);
        if( !$theme_data )
        {
            $theme = new DisHeadCtrl();
            $theme->init($theme_id);
            if( !$theme->ID )
                throw new DisException("对象不存在！$theme_id");
            $theme_data = $theme->info();
            DisNoteDataCache::set_theme_data($theme_id, $theme_data);
        }
        return $theme_data;
    }

    function theme_view()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        $view = $this->detail;

        if( $view['mail_id'] )
        {
            $view['mail'] = DisNoteCtrl::get_mail_view($view['mail_id']);
            $view['mail'][content] = strip_tags($view['mail'][content]);
        }

        if( $view['channel_id'] )
            $view['channel'] = DisChannelCtrl::get_data($view['channel_id']);
        else if( $view['mail']['channel_list'][0] )
            $view['channel'] = $view['mail']['channel_list'][0];
//        if( $user_id > 0 )
//            $view['status'] = $this->check_status($user_id);

        return $view;
    }

    static function parse_themes($theme_ids)
    {
        $theme_list = array();
        $len = count($theme_ids);

        for( $i = 0; $i < $len; $i ++ )
        {
            $theme = DisHeadCtrl::theme($theme_ids[$i]);
            $data = $theme->theme_view();
            $data['last_mail'] = $theme->last_mail();
            array_push($theme_list, $data);
        }
        return $theme_list;
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

    static function new_theme($user_id, $title, $channel_id)
    {
        if( !$user_id || !$title )
            throw new DisParamException("参数不合法！");
        $rsg = '/#([\w\x{4e00}-\x{9fa5}]+)#/ui';
        $theme_content = preg_replace($rsg, '', $title);

        $theme = new DisHeadCtrl();
        $theme->insert($theme_content, 0, (int)$channel_id);
        if( !$theme->ID )
            throw new DisDBException("插入邮件标题失败！");
        $theme->interest($user_id);

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->increase('theme_num');
        return $theme;
    }

    static function theme($theme_id)
    {
        $theme = new DisHeadCtrl();
        $theme->ID = $theme_id;
        $theme->detail = self::get_data($theme_id);
        return $theme;
    }

    function interest($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('没有操作用户！');

        if( $this->check_interest($user_id) )
            return ;
        $interest_id = DisTitleUserData::insert($this->ID, $user_id);
        if( !$interest_id )
            throw new DisException('发生了意外异常！');

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->increase('interest_num');
        $this->increase ('interest_num');

        $theme_ids = DisUserVectorCache::get_interest_theme_ids($user_id);
        array_unshift($theme_ids, $this->ID);

        DisUserVectorCache::set_interest_theme_ids($user_id, $theme_ids);
        DisNoteVectorCache::set_theme_interest_ids($this->ID, null);
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
        DisTitleUserData::remove($this->ID, $user_id);

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->reduce('interest_num');
        $this->reduce ('interest_num');

        DisUserVectorCache::set_interest_theme_ids($user_id, null);
        DisUserVectorCache::set_approved_theme_ids($user_id, null);
        DisNoteVectorCache::set_theme_interest_ids($this->ID, null);
        DisNoteVectorCache::set_theme_approval_ids($this->ID, null);
    }

    function approve($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('没有操作用户！');

        if( $this->check_approved($user_id) )
            return ;
        $approve_id = DisTitleUserData::get_interest_id($this->ID, $user_id);
        if( !$approve_id )
            $approve_id = DisTitleUserData::insert($this->ID, $user_id);
        if( !$approve_id )
            throw new DisException('发生了意外异常！');
        DisTitleUserData::approve($approve_id);

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->increase('approved_num');
        $this->increase ('approved_num');

        $mail = DisNoteCtrl::get_data($this->detail[mail_id]);
        if( $mail[user_id] != $user_id )
        {
            $notice = new DisNoticeCtrl($mail[user_id]);
            $notice->add_approve_notice($approve_id);
        }

        DisUserVectorCache::set_interest_theme_ids($user_id, null);
        DisUserVectorCache::set_approved_theme_ids($user_id, null);
        DisNoteVectorCache::set_theme_interest_ids($this->ID, null);
        DisNoteVectorCache::set_theme_approval_ids($this->ID, null);
    }

    function cancel_approve($user_id)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$user_id )
            throw new DisParamException('没有操作用户！');

        if( !$this->check_approved($user_id) )
            return ;
        $approve_id = DisTitleUserData::get_interest_id($this->ID, $user_id);
        if( !$approve_id )
            return ;
        DisTitleUserData::cancel_approve($approve_id);

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->reduce('approved_num');
        $this->reduce ('approved_num');

        DisUserVectorCache::set_approved_theme_ids($user_id, null);
        DisNoteVectorCache::set_theme_approval_ids($this->ID, null);
    }

    function list_interest_user_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        //pmVectorMemcached::set_follow_theme_ids($user_id, null);
        $user_ids = DisNoteVectorCache::get_theme_interest_ids($this->ID);
        if( !$user_ids )
        {
            $user_ids[0] = "#E#";
            $ids = DisTitleUserData::list_follow_user_ids($this->ID);
            $count = count($ids);
            for( $i = 0; $i < $count; $i ++ )
                $user_ids[$i] = $ids[$i]['user_id'];
            DisNoteVectorCache::set_theme_interest_ids($this->ID, $user_ids);
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
        $user_ids = DisNoteVectorCache::get_theme_approval_ids($this->ID);
        if( !$user_ids )
        {
            $user_ids[0] = "#E#";
            $ids = DisTitleUserData::list_approve_user_ids($this->ID);
            $count = count($ids);
            for( $i = 0; $i < $count; $i ++ )
                $user_ids[$i] = $ids[$i]['user_id'];
            DisNoteVectorCache::set_theme_approval_ids($this->ID, $user_ids);
        }

        if( $user_ids[0] == "#E#" )
            return array();
        return $user_ids;
    }

    function list_mail_ids()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

//        pmVectorMemcached::set_mail_ids($this->ID, null);
        $mail_ids = DisNoteVectorCache::get_mail_ids($this->ID);
        if( !$mail_ids )
        {
            $mail_ids[0] = '#E#';
            $mails = DisNoteCtrl::load_theme_mails($this->ID, 'ID');
            $count = count($mails);
            for( $i = 0; $i < $count; $i ++ )
                $mail_ids[$i] = $mails[$i]['ID'];
            DisNoteVectorCache::set_mail_ids($this->ID, $mail_ids);
        }

        if( $mail_ids[0] == '#E#' )
            $mail_ids = array();
        return $mail_ids;
    }

    function last_mail_id()
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');

        //pmVectorMemcached::set_last_mail_id($root, null);
        $mail_id = DisNoteVectorCache::get_last_mail_id($this->ID);
        if( !$mail_id )
        {
            $last_mails = DisNoteCtrl::last_theme_mail($this->ID);
            if( $last_mails )
                $mail_id = $last_mails['ID'];
            else
                $mail_id = $this->detail['mail_id'];
            DisNoteVectorCache::set_last_mail_id($this->ID, $mail_id);
        }

        return $mail_id;
    }

    function last_mail()
    {
        $mail_id = $this->last_mail_id();
        $mail = DisNoteCtrl::get_mail_view($mail_id);
        $mail[content] = strip_tags($mail[content]);
        return $mail;
    }

    static function list_themes($count = 10)
    {
        $themes = parent::list_themes($count);
        $len = count($themes);
        for( $i = 0; $i < $len; $i ++ )
        {
            $_id = $themes[$i]['channel_id'];
            if( $_id )
                $themes[$i]['channel'] = DisChannelCtrl::get_data($_id);
//            array_push($theme_view, $_view);
        }
        return $themes;
    }

    function list_all_photos()
    {
        $mail_ids = self::list_mail_ids();
        $len = count($mail_ids);
        for( $i = 0, $pk = 0; $i < $len; $i ++ )
        {
            $mail = DisNoteCtrl::get_data($mail_ids[$i]);
            if( !$mail['photo_list'] )
                continue;

            $count = count($mail['photo_list']);
            for( $j = 0; $j < $count; $j ++ )
            {
                $photo = $mail['photo_list'][$j];
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
        $mail_ids = self::list_mail_ids();
        $len = count($mail_ids);
        for( $i = 0, $pk = 0; $i < $len; $i ++ )
        {
            $mail = DisNoteCtrl::get_data($mail_ids[$i]);
            if( !$mail['good_list'] )
                continue;
            $count = count($mail['good_list']);
            for( $j = 0; $j < $count; $j ++ )
            {
                $good = $mail['good_list'][$j];
                $goods[$pk ++] = DisGoodCtrl::get_data($good['good_id']);
            }
        }
        return $goods;
    }
}
?>
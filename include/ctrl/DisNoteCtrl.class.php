<?php
/**
 * @package: DIS.CTRL
 * @file   : DisNoteCtrl.class.php
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

class DisNoteCtrl extends DisNoteData
{
    // 构造函数
    function __construct($mail_id = 0)
    {
        $this->table = "mails";
        parent::__construct($mail_id);
    }

    function increase($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::increase($param, $step);
        DisNoteDataCache::set_mail_data($this->ID, null);
    }

    function reduce($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::reduce($param, $step);
        DisNoteDataCache::set_mail_data($this->ID, null);
    }

    static function parse_mails($mail_ids)
    {
        $mail_list = array();
        $count = count($mail_ids);
        for( $i = 0; $i < $count; $i ++ )
        {
            $mail = DisNoteCtrl::get_mail_view($mail_ids[$i]);
            $mail[content] = strip_tags($mail[content]);
            $theme = DisTitleCtrl::theme($mail['theme_id']);
            $mail[theme] = $theme->info();
            array_push($mail_list, $mail);
        }
        return $mail_list;
    }

    static function get_data($mail_id)
    {
//        pmRowMemcached::set_mail_data($mail_id, null);
        $mail_data = DisNoteDataCache::get_mail_data($mail_id);
        if( !$mail_data )
        {
            $mail = new DisNoteCtrl((int)$mail_id);
            if( !$mail->ID )
                throw new DisException("不存在的邮件！");

            if( $mail->detail['good_num'] )
                $mail->detail['good_list'] = DisNoteGoodData::list_mail_goods($mail_id);
            if( $mail->detail['photo_num'] )
                $mail->detail['photo_list'] = DisNotePhotoData::list_mail_photos($mail_id);
            if( $mail->detail['context'] )
                $mail->detail['parent_list'] = parent::preg_digit_ids($mail->detail['context']);
            if( $mail->detail['channels'] )
                $mail->detail['channel_list'] = parent::preg_channel_list($mail->detail['channels']);

            $mail_data = $mail->detail;
            DisNoteDataCache::set_mail_data($mail_id, $mail_data);
        }

        return $mail_data;
    }

    static function get_mail_view($mail_id)
    {
        try
        {
            $mail = DisNoteCtrl::get_data($mail_id);
            if( (int)$mail['status'] != 0 )
                $mail = array('ID'=>'0', 'content'=>'该邮件已被作者删除！');
//            else
//                $mail[content] = strip_tags($mail[content]);
            $mail['user'] = DisUserCtrl::get_data($mail['user_id']);
        }
        catch (DisException $ex)
        {
            return array('ID'=>'0', 'content'=>'该邮件已被作者删除！');
        }

        if( $mail['good_list'] )
        {
            $count = count($mail['good_list']);
            for( $i = 0; $i < $count; $i ++ )
            {
                $good = $mail['good_list'][$i];
                $good['good'] = DisGoodCtrl::get_data($good['good_id']);
//                $mail['good_list'][$i]['good'] = $good;
                $rank = $mail['good_list'][$i]['rank'];
                $mail['objects'][$rank] = $good; //$mail['good_list'][$i];
                $mail['objects'][$rank]['type'] = 'good';
            }
            unset($mail['good_list']);
        }

        if( $mail['photo_list'] )
        {
            $count = count($mail['photo_list']);
            for( $i = 0; $i < $count; $i ++ )
            {
                $photo = $mail['photo_list'][$i];
                $photo['photo'] = DisPhotoCtrl::get_data($photo['photo_id']);
                $rank = $mail['photo_list'][$i]['rank'];
                $mail['objects'][$rank] = $photo;
                $mail['objects'][$rank]['type'] = 'photo';
            }
            unset($mail['photo_list']);
        }

        if( $mail['channel_list'] )
        {
            $count = count($mail['channel_list']);
            for( $i = 0; $i < $count; $i ++ )
            {
                $channel = $mail['channel_list'][$i];
                $mail['channel_list'][$i] = DisChannelCtrl::get_data($channel['channel_id']);
                $mail['channel_list'][$i]['weight'] = $channel['weight'];
            }
        }

        return $mail;
    }

    static function mail($mail_id)
    {
        $mail = new DisNoteCtrl();
        $mail->ID = $mail_id;
        $mail->detail = self::get_data($mail_id);
        return $mail;
    }

    /**
     * 添加一条邮件
     * @param integer $user_id 发布者ID
     * @param string $title 邮件标题内容
     * @param string $content 信息内容
     * @param integer $channel_id 初始发布的频道ID
     * @param array $photos 图片列表
     * @param array $goods 商品列表
     * @param string $video 视频地址
     * @return DisNoteCtrl 生成的信息对象
     */
    static function new_mail($user_id, $title, $content, $channel_id, $photos = null, $goods = null, $video = "")
    {
        if( !$user_id || !$content || !$title )
            throw new DisParamException("参数不合法！");

        $good_num = $goods ? count($goods) : 0;
        $photo_num = $photos ? count($photos) : 0;
        $theme = DisTitleCtrl::new_theme($user_id, $title, $channel_id);
        $mail = new DisNoteCtrl();
        $mail->insert($user_id, $content, $theme->ID, $photo_num, $good_num, $video);
        if( !$mail->ID )
            throw new DisDBException("插入失败！");
        $theme->update(array('mail_id'=>$mail->ID, 'mail_num'=>1));

        for( $i = 0; $i < $photo_num; $i ++ )
        {
            DisNotePhotoData::insert($mail->ID, $photos[$i]['id'], $photos[$i]['rank'], $photos[$i]['desc']);
            $ph = new DisPhotoCtrl($photos[$i]['id']);
            if( $ph->ID )
                $ph->increase('quote');
        }
        for( $i = 0; $i < $good_num; $i ++ )
        {
            DisNoteGoodData::insert($mail->ID, $goods[$i]['id'], $goods[$i]['rank'], $goods[$i]['desc']);
            $good = new DisGoodCtrl((int)$goods[$i]['id']);
            if( $good->ID )
                $good->increase('quote');
        }
        self::insert_keywords($mail->ID, $title);
        self::insert_keywords($mail->ID, $content);

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->increase("mail_num");
        return $mail;
    }

    /**
     * 回复一条邮件
     * @param integer $parent_mail_id 父邮件的ID
     * @param integer $user_id 发布者ID
     * @param string $content 邮件内容
     * @param array $photos 图片列表
     * @param array $goods 商品列表
     * @param string $video 视频地址
     * @return DisNoteCtrl 新邮件
     */
    function reply($user_id, $content, $photos = null, $goods = null, $video = "")
    {
        if( !$this->ID )
            throw new DisParamException("对象没有初始化！");
        if( !$user_id || !$content )
            throw new DisParamException("参数不合法！");

        $good_num = $goods ? count($goods) : 0;
        $photo_num = $photos ? count($photos) : 0;

        $mail = new DisNoteCtrl();
        $mail->insert($user_id, $content, (int)$this->detail['theme_id'], $photo_num, $good_num,
                $video, (int)$this->ID, (int)$this->detail['depth'] + 1, $this->ID.'#'.$this->detail['context']);
        if( !$mail->ID )
            throw new DisDBException("插入失败！");

        $mail_id = (int)$mail->ID;
        for( $i = 0; $i < $photo_num; $i ++ )
        {
            DisNotePhotoData::insert($mail_id, $photos[$i]['id'], $photos[$i]['rank'], $photos[$i]['desc']);
            $ph = new DisPhotoCtrl($photos[$i]['id']);
            if( $ph->ID )
                $ph->increase('quote');
        }
        for( $i = 0; $i < $good_num; $i ++ )
        {
            DisNoteGoodData::insert($mail_id, $goods[$i]['id'], $goods[$i]['rank'], $goods[$i]['desc']);
            $good = new DisGoodCtrl((int)$goods[$i]['id']);
            if( $good->ID )
                $good->increase('quote');
        }
        self::insert_keywords($mail_id, $content);

        DisNoteVectorCache::set_mail_ids($this->detail['theme_id'], null);
        DisNoteVectorCache::set_child_mail_ids($this->ID, null);
        $this->increase('reply_num');

        $param = new DisUserParamCtrl();
        $param->ID = $user_id;
        $param->increase("mail_num");

        if( $this->detail['user_id'] != $user_id )
        {
            $mail_user = DisUserCtrl::user((int)$this->detail['user_id']);
            $mail_user->reply_notice($mail_id);
        }

        $theme = DisTitleCtrl::theme((int)$this->detail['theme_id']);
        $theme->increase('mail_num');

        $user_ids = $theme->list_interest_user_ids();
        $len = count($user_ids);
        for( $i = 0; $i < $len; $i ++ )
        {
            if( $user_ids[$i] == $user_id )
                continue;
            $notice = new DisNoticeCtrl($user_ids[$i]);
            $notice->add_mail_notice($mail_id);
        }

        return $mail;
    }

    /**
     * 新版的发布信息
     * @param <integer> $user_id 用户ID
     * @param <array> $channel_ids 频道ID列表
     * @param <integer> $weight 邮件的优先级
     * @return <integer> 发布ID
     */
    function send($user_id, $channel_id, $weight = 0)
    {
        if( !$this->ID )
            throw new DisParamException("对象没有初始化！");
        if( !$user_id || !$channel_id )
            throw new DisParamException("参数不合法！");

        $cu = new DisChanUserCtrl($user_id);
        $joined_ids = $cu->list_joined_ids( );
        if( !in_array($channel_id, $joined_ids) )
            throw new DisParamException('你没有加入这个频道，无权发送邮件');
        $flow = new DisNoteFlowCtrl();
        $flow_id = $flow->insert($user_id, $this->ID, $channel_id, $weight);

        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        $num = (int)$this->detail["publish_num"] + 1;
        $channels = $this->detail['channels']."$channel_id|$weight#";
        $this->update(array("publish_num"=>$num, "channels"=>$channels));

        if( $weight > 0 )
        {
            $param = new DisUserParamCtrl($user_id);
            $param->pay_money($weight);
        }
        $channel = DisChannelCtrl::channel($channel_id);
        $channel->increase("mail_num");

        $feed = DisFeedCtrl::read_ctrler($user_id);
        $feed->push_flow($flow_id);
        DisFeedCtrl::save_ctrler($feed);

        DisNoteVectorCache::set_mail_ids($this->detail[theme_id], null);
        DisNoteDataCache::set_mail_data($this->ID, null);
        DisUserVectorCache::set_publish_mail_ids($user_id, null);
        return $flow_id;
    }

    /**
     * 保存信息所含有的关键词
     * @param integer $mail_id 信息ID
     * @param string $content 信息内容
     * @return integer 插入关键词的个数
     */
    static function insert_keywords($mail_id, $content)
    {
        $rsg = '/#([\w\x{4e00}-\x{9fa5}]+)#/ui';
        if( preg_match_all($rsg, $content, $matches) )
            $keywords = $matches[1];
        else
            return 0;

        $len = count($keywords);
        for( $r = 0, $i = 0; $i < $len; $i ++ )
            $r += soNewsKeywordDataTable::insert($mail_id, $keywords[$i]);
        return $r;
    }

    function list_child_ids()
    {
        if( !$this->ID )
            throw new DisParamException("对象没有初始化！");

        //pmVectorMemcached::set_child_mail_ids($this->ID, null);
        $child_ids = DisNoteVectorCache::get_child_mail_ids($this->ID);
        if( !$child_ids )
        {
            $child_ids[0] = '#E#';
            $children = parent::load_mail_replies($this->ID, "ID");
            $count = count($children);
            for ( $i = 0; $i < $count; $i ++ )
                $child_ids[$i] = $children[$i]['ID'];
            DisNoteVectorCache::set_child_mail_ids($this->ID, $child_ids);
        }

        if( $child_ids[0] == '#E#' )
            $child_ids = array();
        return $child_ids;
    }

    function list_parent_ids()
    {
        if( !$this->ID )
            throw new DisParamException("对象没有初始化！");

        $parent_mids = DisNoteVectorCache::get_parent_mail_ids($this->ID);
        if( !$parent_mids )
        {
            $parent_mids[0] = "#E#";
            if( !$this->detail )
                $this->detail = self::get_data($this->ID);

            if( $this->detail['depth'] > 0 && preg_match_all("/(\d+)#/", $this->detail['context'], $matches) )
            {
                $count = count($matches[1]);
                for( $i = 0; $i < $count; $i ++ )
                    $parent_mids[$i] = $matches[1][$i];
                $parent_mids = array_reverse($parent_mids);
            }

            DisNoteVectorCache::set_parent_mail_ids($this->ID, $parent_mids);
        }

        if( $parent_mids[0] == "#E#" )
            return array();
        return $parent_mids;
    }

    static function list_flow_ids_by_keyword($keyword, $page = 0, $size = 20)
    {
        return DisNoteKeywordData::list_releases($keyword, $page, $size);
    }
}
?>
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

class DisNoteCtrl extends DisInfoNoteData
{
    // 构造函数
    function __construct($note_id = 0)
    {
        parent::__construct($note_id);
    }

    function increase($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::increase($param, $step);
        DisNoteDataCache::set_note_data($this->ID, null);
    }

    function reduce($param, $step = 1)
    {
        if( !$this->ID )
            throw new DisParamException('对象没有初始化！');
        if( !$this->detail )
            $this->detail = self::get_data($this->ID);
        parent::reduce($param, $step);
        DisNoteDataCache::set_note_data($this->ID, null);
    }

    static function parse_mails($note_ids)
    {
        $count = count($note_ids);
        $info_list = array();
        for( $i = 0; $i < $count; $i ++ )
        {
            $note = DisNoteCtrl::get_note_view($note_ids[$i]);
            $note[content] = strip_tags($note[content]);
            $head = DisHeadCtrl::head($note['head_id']);
            $note[theme] = $head->info();
            array_push($info_list, $note);
        }
        return $info_list;
    }

    static function get_data($note_id)
    {
//        pmRowMemcached::set_mail_data($note_id, null);
        $note_data = DisNoteDataCache::get_note_data($note_id);
        if( !$note_data )
        {
            $note = new DisNoteCtrl((int)$note_id);
            if( !$note->ID )
                throw new DisParamException("不存在的信息！");

            if( $note->detail['good_num'] )
                $note->detail['good_list'] = DisInfoGoodData::list_mail_goods($note_id);
            if( $note->detail['photo_num'] )
                $note->detail['photo_list'] = DisInfoPhotoData::list_mail_photos($note_id);
//            if( $mail->detail['context'] )
//                $mail->detail['parent_list'] = parent::preg_digit_ids($mail->detail['context']);
//            if( $mail->detail['channels'] )
//                $mail->detail['channel_list'] = parent::preg_channel_list($mail->detail['channels']);

            $note_data = $note->detail;
            DisNoteDataCache::set_note_data($note_id, $note_data);
        }

        return $note_data;
    }

    static function get_note_view($note_id)
    {
        try
        {
            $note = DisNoteCtrl::get_data($note_id);
            if( (int)$note['status'] != 0 )
                $note = array('ID'=>'0', 'content'=>'该信息已被作者删除！');
//            else
//                $mail[content] = strip_tags($mail[content]);
            $note['user'] = DisUserCtrl::get_data($note['user_id']);
        }
        catch (DisException $ex)
        {
            return array('ID'=>'0', 'content'=>'该信息已被作者删除！');
        }

        if( $note['good_list'] )
        {
            $count = count($note['good_list']);
            for( $i = 0; $i < $count; $i ++ )
            {
                $good = $note['good_list'][$i];
                $good['good'] = DisGoodCtrl::get_data($good['good_id']);
//                $mail['good_list'][$i]['good'] = $good;
                $rank = $note['good_list'][$i]['rank'];
                $note['objects'][$rank] = $good; //$mail['good_list'][$i];
                $note['objects'][$rank]['type'] = 'good';
            }
            unset($note['good_list']);
        }

        if( $note['photo_list'] )
        {
            $count = count($note['photo_list']);
            for( $i = 0; $i < $count; $i ++ )
            {
                $photo = $note['photo_list'][$i];
                $photo['photo'] = DisPhotoCtrl::get_data($photo['photo_id']);
                $rank = $note['photo_list'][$i]['rank'];
                $note['objects'][$rank] = $photo;
                $note['objects'][$rank]['type'] = 'photo';
            }
            unset($note['photo_list']);
        }

        if( $note['channel_list'] )
        {
            $count = count($note['channel_list']);
            for( $i = 0; $i < $count; $i ++ )
            {
                $channel = $note['channel_list'][$i];
                $note['channel_list'][$i] = DisChannelCtrl::get_data($channel['channel_id']);
                $note['channel_list'][$i]['weight'] = $channel['weight'];
            }
        }

        return $note;
    }

    static function note($note_id)
    {
        $note = new DisNoteCtrl();
        $note->ID = $note_id;
        $note->detail = self::get_data($note_id);
        return $note;
    }

    /**
     * 添加一条新信息
     * @param integer $user_id 发布者ID
     * @param integer $channel_id 初始发布的频道ID
     * @param string $title 邮件标题内容
     * @param string $content 信息内容
     * @param array $photos 图片列表
     * @param array $goods 商品列表
     * @param string $video 视频地址
     * @return DisNoteCtrl 生成的信息对象
     */
    static function new_info($user_id, $chan_id, $title, $content, $photos = null,
            $goods = null, $video = "")
    {
        if( !$user_id || !$content || !$title )
            throw new DisParamException("参数不合法！");

        $photo_num = $photos ? count($photos) : 0;
        $good_num = $goods ? count($goods) : 0;

        $head = DisHeadCtrl::new_head($user_id, $chan_id, $title);
        $note = new DisNoteCtrl();
        $note->insert($user_id, $content, $head->ID, 0, $photo_num, $good_num, $video);
        if( !$note->ID )
            throw new DisDBException("插入失败！");
        $head->update(array('note_id'=>$note->ID, 'note_num'=>1));

        for( $i = 0; $i < $photo_num; $i ++ )
        {
            DisInfoPhotoData::insert($note->ID, $photos[$i]['id'], $photos[$i]['rank'],
                    $photos[$i]['desc']);
            $ph = new DisPhotoCtrl($photos[$i]['id']);
            if( $ph->ID )
                $ph->increase('quote');
        }
        for( $i = 0; $i < $good_num; $i ++ )
        {
            DisInfoGoodData::insert($note->ID, $goods[$i]['id'], $goods[$i]['rank'],
                    $goods[$i]['desc']);
            $good = new DisGoodCtrl((int)$goods[$i]['id']);
            if( $good->ID )
                $good->increase('quote');
        }

//        self::insert_keywords($note->ID, $title);
//        self::insert_keywords($note->ID, $content);

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->increase("note_num");
        return $note;
    }

    /**
     * 回复一条邮件
     * @param integer $parent_note_id 父邮件的ID
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

        $note = new DisNoteCtrl();
        $note->insert($user_id, $content, (int)$this->detail['head_id'], (int)$this->ID,
                $photo_num, $good_num, $video);//, (int)$this->detail['depth'] + 1, $this->ID.'#'.$this->detail['context']);
        if( !$note->ID )
            throw new DisDBException("插入失败！");
        $note_id = (int)$note->ID;

        for( $i = 0; $i < $photo_num; $i ++ )
        {
            DisInfoPhotoData::insert($note_id, $photos[$i]['id'], $photos[$i]['rank'],
                    $photos[$i]['desc']);
            $ph = new DisPhotoCtrl($photos[$i]['id']);
            if( $ph->ID )
                $ph->increase('quote');
        }
        for( $i = 0; $i < $good_num; $i ++ )
        {
            DisInfoGoodData::insert($note_id, $goods[$i]['id'], $goods[$i]['rank'],
                    $goods[$i]['desc']);
            $good = new DisGoodCtrl((int)$goods[$i]['id']);
            if( $good->ID )
                $good->increase('quote');
        }
//        self::insert_keywords($note_id, $content);

        $this->increase('reply_num');

        $head = DisHeadCtrl::head((int)$this->detail['head_id']);
        $head->increase('note_num');

        $param = new DisUserParamCtrl($user_id);
//        $param->ID = $user_id;
        $param->increase("note_num");

        if( $this->detail['user_id'] != $user_id )
        {
            DisInfoReplyData::insert($note_id, (int)$this->detail['user_id']);
            $notice = new DisNoticeCtrl((int)$this->detail['user_id']);
            $notice->add_reply_notice($note_id);
        }

        DisNoteVectorCache::set_note_ids($this->detail['head_id'], null);
        DisNoteVectorCache::set_child_note_ids($this->ID, null);
        return $note;
//            $user = DisUserCtrl::user((int)$this->detail['user_id']);
//            $user->reply_notice($note_id);
//        $user_ids = $head->list_interest_user_ids();
//        $len = count($user_ids);
//        for( $i = 0; $i < $len; $i ++ )
//        {
//            if( $user_ids[$i] == $user_id )
//                continue;
//            $notice = new DisNoticeCtrl($user_ids[$i]);
//            $notice->add_mail_notice($note_id);
//        }
    }

    /**
     * 新版的发布信息
     * @param <integer> $user_id 用户ID
     * @param <integer> $channel_id 频道ID
     * @param <integer> $weight 邮件的优先级
     * @return <integer> 发布ID
     */
    function send($user_id, $chan_id, $weight = 0)
    {
        if( !$this->ID )
            throw new DisParamException("对象没有初始化！");
        if( !$user_id || !$chan_id )
            throw new DisParamException("参数不合法！");

        $cu = new DisChanUserCtrl( $user_id );
        $joined_ids = $cu->list_joined_ids( );
        if( !in_array($chan_id, $joined_ids) )
            throw new DisParamException('你没有加入这个频道，无权发送邮件');
        $flow = new DisStreamCtrl();
        $flow_id = $flow->insert($user_id, $this->ID, $chan_id, $weight);

//        if( !$this->detail )
//            $this->detail = self::get_data($this->ID);
//        $num = (int)$this->detail["publish_num"] + 1;
//        $channels = $this->detail['channels']."$channel_id|$weight#";
//        $this->update(array("publish_num"=>$num, "channels"=>$channels));
//        $channel = DisChannelCtrl::channel($channel_id);
//        $channel->increase("mail_num");

        if( $weight > 0 )
        {
            $param = new DisUserParamCtrl($user_id);
            $param->pay_money($weight);
        }
        $feed = DisFeedCtrl::read_ctrler($user_id);
        $feed->push_flow($flow_id);
        DisFeedCtrl::save_ctrler($feed);

        DisNoteVectorCache::set_note_ids($this->detail[head_id], null);
        DisNoteDataCache::set_note_data($this->ID, null);
        DisUserVectorCache::set_publish_note_ids($user_id, null);
        return $flow_id;
    }

    /**
     * 保存信息所含有的关键词
     * @param integer $note_id 信息ID
     * @param string $content 信息内容
     * @return integer 插入关键词的个数
     */
    static function insert_keywords($note_id, $content)
    {
        $rsg = '/#([\w\x{4e00}-\x{9fa5}]+)#/ui';
        $matches = null;
        if( preg_match_all($rsg, $content, $matches) )
            $keywords = $matches[1];
        else
            return 0;

        $len = count($keywords);
        for( $r = 0, $i = 0; $i < $len; $i ++ )
            $r += soNewsKeywordDataTable::insert($note_id, $keywords[$i]);
        return $r;
    }

    function list_child_ids()
    {
        if( !$this->ID )
            throw new DisParamException("对象没有初始化！");

        //pmVectorMemcached::set_child_note_ids($this->ID, null);
        $child_ids = DisNoteVectorCache::get_child_note_ids($this->ID);
        if( !$child_ids )
        {
            $child_ids[0] = '#E#';
            $children = parent::load_note_replies($this->ID, "ID");
            $count = count($children);
            for ( $i = 0; $i < $count; $i ++ )
                $child_ids[$i] = $children[$i]['ID'];
            DisNoteVectorCache::set_child_note_ids($this->ID, $child_ids);
        }

        if( $child_ids[0] == '#E#' )
            $child_ids = array();
        return $child_ids;
    }

    function list_parent_ids()
    {
        if( !$this->ID )
            throw new DisParamException("对象没有初始化！");

        $parent_mids = DisNoteVectorCache::get_parent_note_ids($this->ID);
        if( !$parent_mids )
        {
            $parent_mids[0] = "#E#";
            if( !$this->detail )
                $this->detail = self::get_data($this->ID);
            $matches = null;

            if( $this->detail['depth'] > 0 && preg_match_all("/(\d+)#/", $this->detail['context'], $matches) )
            {
                $count = count($matches[1]);
                for( $i = 0; $i < $count; $i ++ )
                    $parent_mids[$i] = $matches[1][$i];
                $parent_mids = array_reverse($parent_mids);
            }

            DisNoteVectorCache::set_parent_note_ids($this->ID, $parent_mids);
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
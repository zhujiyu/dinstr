<?php
/**
 * @package: DIS.DATA
 * @file   : DisInfoHeadData.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisInfoHeadData extends DisDBTable
{
    protected static $stable = "info_heads";

    function __construct($ID = null)
    {
        $this->table = "info_heads";
        parent::__construct($ID);
    }

    protected function _strip_tags($detail)
    {
        $detail['content'] = strip_tags($detail['content']);
    }

    function init($id, $slt = "ID, user_id, chan_id, weight, status, note_id, content, 
        note_num, interest_num, approved_num, unix_timestamp(create_time) as create_time")
    {
        parent::init($id, $slt);
    }

    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'content':
                if( !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'user_id':
            case 'chan_id':
            case 'note_id':
            case 'weight':
            case 'status':
            case 'note_num':
                if( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            default:
                return err(DIS_ERR_PARAM);
        }
        return err(DIS_SUCCEEDED);
    }

    protected function _check_num_param($param)
    {
        return in_array($param, array('interest_num', 'approved_num', 'note_num'));
    }

    function new_head($title, $note_id, $user_id, $chan_id, $weight = 0)
    {
        if( !$note_id || !$title )
            throw new DisParamException("参数不合法！");
        return parent::insert(array('content'=>$title, 'note_id'=>$note_id,
            'user_id'=>$user_id,  'chan_id'=>$chan_id, 'weight'=>$weight));
    }
    
    function publish()
    {
        $this->update(array('status'=>1));
    }

    static function list_publish_infos($user_id)
    {
        $str = "select ID, user_id, chan_id, weight, status, note_id, content, 
            note_num, interest_num, approved_num, create_time
            from ".DisInfoHeadData::$stable." where user_id = $user_id and status > 0";
        return parent::load_datas($str);
    }
    
//    function new_head($title, $note_id, $user_id, $chan_id, $weight = 0, $status = 0)
//    {
//        if( !$note_id || !$title )
//            throw new DisParamException("参数不合法！");
//        $rsg = '/#([\w\x{4e00}-\x{9fa5}]+)#/ui';
//        $title = preg_replace($rsg, '', $title);
//
//        $head = new DisHeadCtrl();
//        $head->insert($title, $note_id);
//        if( !$head->ID )
//            throw new DisDBException("插入信息头失败！");
//
//        return $head;
//    }
//
//    function insert($content, $note_id, $user_id, $chan_id, $weight = 0, $status = 0)
//    {
//        return parent::insert(array('content'=>$content, 'note_id'=>$note_id, 
//            'user_id'=>$user_id, 'chan_id'=>$chan_id, 'weight'=>$weight, 'status'=>$status));
//    }
//
//    protected static function list_channel_themes($channel_id, $page = 0, $count = 40)
//    {
//        $str = "select ID from ".DisHeadData::$stable." where channel_id = $channel_id order by ID desc limit ".$page*$count.", $count";
//        return parent::load_datas($str);
//    }
//
//    static function list_themes($count = 10)
//    {
//        $str = "select * from ".DisInfoHeadData::$stable." order by id desc limit $count";
//        return parent::load_datas($str);
//    }
}
?>
<?php
/**
 * @package: DIS.DATA
 * @file   : DisHeadData.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisHeadData extends DisDBTable
{
    protected static $stable = "heads";

    function __construct($ID = null)
    {
        //DisHeadData::$stable = "heads";
        $this->table = "heads";
        parent::__construct($ID);
    }

    protected function _strip_tags($detail)
    {
        $detail['content'] = strip_tags($detail['content']);
    }

    function init($id, $slt = "ID, note_id, content, interest_num, note_num, approved_num, update_time")
    {
        parent::init($id, $slt);
    }

    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'content' :
                if( !is_string($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            case 'note_id' :
            case 'note_num' :
                if( !is_integer($value) )
                    return err(PMAIL_ERR_PARAM);
                break;
            default :
                return err(PMAIL_ERR_PARAM);
        }
        return err(PMAIL_SUCCEEDED);
    }

    protected function _check_num_param($param)
    {
        return in_array($param, array('interest_num', 'approved_num', 'note_num'));
    }

    function insert($content, $note_id = 0)
    {
        return parent::insert(array('content'=>$content, 'note_id'=>$note_id));
    }

//    protected static function list_channel_themes($channel_id, $page = 0, $count = 40)
//    {
//        $str = "select ID from ".DisHeadData::$stable." where channel_id = $channel_id order by ID desc limit ".$page*$count.", $count";
//        return parent::load_datas($str);
//    }

    static function list_themes($count = 10)
    {
        $str = "select * from ".DisHeadData::$stable." order by id desc limit $count";
        return parent::load_datas($str);
    }
}
?>
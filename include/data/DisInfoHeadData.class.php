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

    function init($id, $slt = "ID, content, note_id, note_num, interest_num, approved_num")
    {
        parent::init($id, $slt);
    }

    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'content' :
                if( !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'note_id' :
            case 'note_num':
                if( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            default :
                return err(DIS_ERR_PARAM);
        }
        return err(DIS_SUCCEEDED);
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
//
//    static function list_themes($count = 10)
//    {
//        $str = "select * from ".DisInfoHeadData::$stable." order by id desc limit $count";
//        return parent::load_datas($str);
//    }
}
?>
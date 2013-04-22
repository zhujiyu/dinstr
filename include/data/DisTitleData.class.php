<?php
/**
 * @package: DIS.DATA
 * @file   : DisTitleData.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisTitleData extends DisDBTable
{
    function __construct($ID = null)
    {
        $this->table = "themes";
        parent::__construct($ID);
    }

    protected function _strip_tags($detail)
    {
        $detail['content'] = strip_tags($detail['content']);
    }

    function init($id, $slt = "ID, mail_id, content, channel_id, interest_num, mail_num, approved_num, update_time")
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
            case 'mail_id' :
            case 'mail_num' :
            case 'channel_id' :
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
        return in_array($param, array('interest_num', 'approved_num', 'mail_num'));
    }

    function insert($content, $mail_id = 0, $channel_id = 0)
    {
        return parent::insert(array('content'=>$content, 'mail_id'=>$mail_id, 'channel_id'=>$channel_id));
    }

    protected static function list_channel_themes($channel_id, $page = 0, $count = 40)
    {
        $str = "select ID from themes where channel_id = $channel_id order by ID desc limit ".$page*$count.", $count";
        return parent::load_datas($str);
    }

    static function list_themes($count = 10)
    {
        $str = "select * from themes order by id desc limit $count";
        return parent::load_datas($str);
    }
}
?>
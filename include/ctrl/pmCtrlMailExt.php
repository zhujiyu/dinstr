<?php
/**
 * @package: DIS.CTRL
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisNoteCollectCtrl extends pmDataMailCollect
{
    function  __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    function insert($mail_id)
    {
        parent::insert($mail_id);
        $param = new DisUserParamCtrl();
        $param->ID = $this->user_id;
        $param->increase("collect_num");
    }

    function delete($mail_id)
    {
        parent::delete($mail_id);
        $user = DisUserCtrl::user($this->user_id);
        $user->reduce('collect_num');
    }

    function list_mail_ids($page = 0, $count = 20)
    {
        $mail_ids = array();
        $mails = parent::list_mails($page, $count);
        $len = count($mails);
        for( $i = 0; $i < $len; $i ++ )
        {
            $mail_ids[] = $mails[$i]['mail_id'];
        }
        return $mail_ids;
    }
}
?>

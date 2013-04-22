<?php
/**
 * Encoding     :   UTF-8
 * Author       :   zhujiyu , zhujiyu@139.com
 * Created on   :   2012-7-3 2:14:00
 * Copyright    :   2011 社交化协同服务办公系统项目
 */
require_once '../common.inc.php';

ob_start();
try
{
    if( isset($_SESSION['userId']) && $_SESSION['userId'] > 0 && DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        $user_id = $_SESSION['userId'];
        DisUserCtrl::set_inline($user_id);
        $feed = DisFeedCtrl::read_ctrler($user_id);
    }
    else
    {
        $user_id = 0;
        $cuCookie = json_decode( $_COOKIE['guest-subscribes'] );
        $chanids = $cuCookie->ids;
        $feed = DisGuestFeedCtrl::read_ctrler($chanids);
    }

    $p = $_REQUEST['p'] ? $_REQUEST['p']: 'refrush';
    if( isset($_GET['list']) || $p == 'list' )
    {
        $start = $_REQUEST['start'] ? $_REQUEST['start'] : 0;
        $count = $_REQUEST['count'] ? $_REQUEST['count'] : 100;

        if( $user_id > 0 )
            $val['feeded'] =  $feed->list_flow_ids($start, $count);
        else
            $val['feeded'] =  $feed->list_flow_ids($chanids, $start, $count);
    }
    else if( isset($_GET['refrush']) || $p == 'refrush' )
    {
        $feed->feed_new();
        $val['tofeed'] =  $feed->mem_flows;
    }
    else if( isset($_GET['read']) || $p == 'read' )
        $feed->read_feed();
    else
        throw new DisException('错误的类型');

    if( $user_id > 0 )
        DisFeedCtrl::save_ctrler($feed);
    else
        DisGuestFeedCtrl::save_ctrler($feed);
}
catch (DisException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$val['err'] = $err;
echo json_encode($val);
?>
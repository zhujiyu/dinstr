<?php
/**
 * @package: DIS.CTRL
 * @file   : DisGuestFeedCtrl.class.php
 * @abstract:
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

/**
 * 处理游客的信息时间线
 * 用户登录后，将信息拉过来，步骤如下：
 * 1、查询上次拉信息的时间点，
 * 2、从上次查询的时间点，到现在，一次性最多将400条信息ID加进feed表中
 * 3、若上次处理的时间较远，本次查询到超过400条，
 * 则将以前的数据删除掉，以前的数据已经过时了
 */
class DisGuestFeedCtrl extends DisGuestFeedData
{
    var $mem_flag = 0;
    var $his_flag = 0;
    var $chanids = array();
    var $mem_flows  = array();
    var $follow_ids = array();

    function __construct()
    {
        parent::__construct();
    }

    static function read_ctrler($chanids)
    {
        if( !isset ($_SESSION['guest-feed']) || $_SESSION['guest-feed'] == null )
        {
            $feed = new DisGuestFeedCtrl();
            $feed->chanids = $chanids;
            $feed->start_feed();
        }
        else
            $feed = unserialize($_SESSION['guest-feed']);
        return $feed;
    }

    static function save_ctrler($feed)
    {
        $_SESSION['guest-feed'] = serialize($feed);
    }

    function start_feed()
    {
        $this->mem_flag = (int)(time() / DisConfigAttr::$intervals['five']);
        $this->his_flag = (int)(time() / DisConfigAttr::$intervals['day']);

        $start = $this->his_flag * DisConfigAttr::$intervals['day'];
        $end = $this->mem_flag * DisConfigAttr::$intervals['five'];
        $this->follow_ids = $this->_hist_flows($start, $end);
    }

    private function _merge($flow_ids1, $flow_ids2)
    {
        $i1 = 0; $i2 = 0;
        $flow_ids = array();
        $flen1 = count($flow_ids1);
        $flen2 = count($flow_ids2);

        while( $i1 < $flen1 && $i2 < $flen2 )
        {
            if( $flow_ids1[$i1] > $flow_ids2[$i2] )
            {
                array_push($flow_ids, $flow_ids1[$i1]);
                $i1 ++;
            }
            else
            {
                array_push($flow_ids, $flow_ids2[$i2]);
                $i2 ++;
            }
        }

        if( $i1 < $flen1 )
        {
            $temp = array_slice($flow_ids1, $i1);
            $flow_ids = array_merge ($flow_ids, $temp);
        }
        if( $i2 < $flen2 )
        {
            $temp = array_slice($flow_ids2, $i2);
            $flow_ids = array_merge ($flow_ids, $temp);
        }

        return $flow_ids;
    }

    private function merge_list($flowids_list)
    {
        $flen = count($flowids_list);
        if( $flen < 1 )
            return array();
        $flow_ids = $flowids_list[0];

        for( $i = 1; $i < $flen; $i ++ )
            $flow_ids = $this->_merge($flow_ids, $flowids_list[$i]);
        return $flow_ids;
    }

    protected function _hist_flows($start, $end)
    {
        $len = count($this->chanids);
        if( $len < 1 )
            return array();

        for( $i = 0; $i < $len; $i ++ )
        {
            $chan_id = $this->chanids[$i];
            if( !is_int($chan_id) )
                continue;
            $flow_ids = DisFeedVectorCache::get_flow_ids($chan_id, $start, $end);

            if( !$flow_ids )
            {
                $flows = parent::load($chan_id, $start, $end);
                $flow_ids = chunk_array($flows, 'flow_id');
                DisFeedVectorCache::set_flow_ids($chan_id, $start, $end, $flow_ids);
            }
            $flowid_list[] = $flow_ids;
        }

        return $this->merge_list($flowid_list);
    }

    function list_flow_ids($chanids, $start, $count)
    {
        $len = count($this->follow_ids);
        $this->chanids = $chanids;

        if( $len < $start + $count )
        {
            $stime = $this->his_flag * DisConfigAttr::$intervals['day'];
            $etime = $stime + DisConfigAttr::$intervals['day'];
            $follow_ids = $this->_hist_flows($stime, $etime);
            $this->follow_ids = array_merge($this->follow_ids, $follow_ids);

            for( $c = 0, $len += count($follow_ids); $len < $start + $count && $c < 10; $c ++ )
            {
                $this->his_flag -= 7;
                $etime = $stime;
                $stime = $etime - DisConfigAttr::$intervals['week'];
                $flow_ids = $this->_hist_flows($stime, $etime);

                $_len = count($flow_ids);
                if( $_len < 1 )
                    continue;
                $len += $_len;
                $this->follow_ids = array_merge($this->follow_ids, $flow_ids);
            }
        }

        return list_slice($this->follow_ids, $start, $count);
    }

    function feed_new()
    {
        $flag = (int)(time() / DisConfigAttr::$intervals['five']);
        if( $flag > $this->mem_flag )
        {
            $start = $this->mem_flag * DisConfigAttr::$intervals['five'];
            $end = $flag * DisConfigAttr::$intervals['five'];
            $flow_ids = $this->_hist_flows($start, $end);

            if( count($flow_ids) > 0 )
                $this->mem_flows = array_merge($flow_ids, $this->mem_flows);
            $this->mem_flag = $flag;
        }
        return $this->mem_flows;
    }

    function read_feed()
    {
        if( count($this->mem_flows) > 0 )
            $this->follow_ids = array_merge($this->mem_flows, $this->follow_ids);
        $this->mem_flows = array();
    }
}
?>

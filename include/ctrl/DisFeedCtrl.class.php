<?php
/**
 * @package: DIS.CTRL
 * @file   : DisFeedCtrl.class.php
 * @abstract  : 用户接口
 *
 * Feed算法 2011-10-12
 *
Feed 3.0 基于memcached的Feed算法

Feed时间段：

    Feed运算，每T分钟执行一次，每次执行，对过去一个完整
的T时间段的信息进行运算：
    feed_end_time = current_time / T * T;
    feed_start_time = feed_end_time - T;
    因此，信息总是存在迟延，最大迟延达到2T。

信息加载：

    当缓存中没有Feed时段的信息时，启动加载进程，从数据库中
加载各频道的信息ID，加载的数据是按照频道ID升序排列的；

Feed过程：

    1、用户订阅的频道列表，称为C列表，Feed信息列表，称为F列表，它们都是按照频道ID的升序排列好的；
    2、当C列表和F列表的长度都不大于20时，对两个列表一起进行遍历，找出关注的信息；
    3、若C列表和F列表有一个大于20时，对大的列表进行折半分解，对每一半，找到在另一个列表中的对应列表，转2；
    4、若两个列表都大于20时，首先按照F列表进行递归折半，直到出现3或者2的情况，转3或者2；
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
 * 以下几个函数实现了Feed算法，
 * 在数学上，可以抽象成两个集合求交集。
 */
/**
 * Feed算法的核心单元，当U列表和F列表都较小时，直接进行双遍历
 *
 * @param array $ulist 关注的明星用户列表，或者加入/订阅的群组列表
 * @param array $flist Feed信息列表
 * @param array $data 两个数组当前的处理的索引的上下界限，顺序为：（ulist最小索引，ulist最大索引，flist最小索引，flist最大索引）
 * @param string $type Feed信息列表中发布信息主题的键名
 * @return array 返回Follow 的信息列表
 */
function _feed_small_core($ulist, $flist, $data)
{
    $nlist = array();
    $ui = $data[0];
    $ulen = $data[1];
    $fi = $data[2];
    $flen = $data[3];

    while ( $ui <= $ulen && $fi <= $flen )
    {
        if( $ulist[$ui] == $flist[$fi] )
        {
            array_push($nlist, $fi);
            $fi ++ ;
        }
        else if( $ulist[$ui] > $flist[$fi] )
            $fi ++;
        else
            $ui ++;
    }

    return $nlist;
}

/**
 * 关注数小于10的时候 实质是一个小集合和一个大集合求交的运算
 *
 * @param array $ulist 关注用户ID列表 升序 小集合
 * @param array $flist 信息发布者ID列表 升序 大集合
 * @param array $data 两个列表的索引范围
 * @return array 返回在关注用户ID表中的信息发布者ID列表的索引表
 */
function feed_small_follows($ulist, $flist, $data)
{
    $nlist = array();
    $i = $data[0];
    $j = $data[1];
    $k = $data[2];
    $h = $data[3];

    while ( $i <= $j && $k <= $h )
    {
        if( $ulist[$i] > $flist[$h] || $ulist[$j] < $flist[$k] )
            return $nlist;

        if( $ulist[$i] > $flist[$k] )
        {
            $k = _asc_bin_search($flist, $ulist[$i], $k, $h);
            if( $ulist[$i] > $flist[$k] )
                $k ++;
        }
        if( $ulist[$j] < $flist[$h] )
        {
            $h = _asc_bin_search($flist, $ulist[$j], $k, $h);
        }

        while( $ulist[$i] == $flist[$k] && $k <= $h )
        {
            array_push($nlist, $k);
            $k ++;
        }
        while( $ulist[$j] == $flist[$h] && $k <= $h )
        {
            array_push($nlist, $h);
            $h --;
        }

        while( $ulist[$i] < $flist[$k] && $i <= $j )
            $i ++;
        while( $ulist[$j] > $flist[$h] && $i <= $j )
            $j --;
    }
    return $nlist;
}

/**
 * 信息数不足10的时候 实质是一个小集合和一个大集合求交的运算
 *
 * @param array $ulist 关注用户ID列表 升序 大集合
 * @param array $flist 信息发布者ID列表 升序 小集合
 * @param array $data 两个列表的索引范围
 * @return array 返回在关注用户ID表中的信息发布者ID列表的索引表
 */
function feed_small_news($ulist, $flist, $data)
{
    $nlist = array();
    $i = $data[0];
    $j = $data[1];
    $k = $data[2];
    $h = $data[3];

    while( $i <= $j && $k <= $h )
    {
        if( $flist[$h] < $ulist[$i] || $flist[$k] > $ulist[$j] )
            return $nlist;

        if( $flist[$k] > $ulist[$i] )
        {
            $i = _asc_bin_search($ulist, $flist[$k], $i, $i);
            if( $flist[$k] > $ulist[$i] )
                $i ++;
        }
        if( $flist[$h] < $ulist[$j] )
        {
            $j = _asc_bin_search($ulist, $flist[$h], $i, $j);
        }

        while( $ulist[$i] == $flist[$k] && $k <= $h )
        {
            array_push($nlist, $k);
            $k ++;
        }
        while( $ulist[$j] == $flist[$h] && $k <= $h )
        {
            array_push($nlist, $h);
            $h --;
        }

        while( $flist[$k] < $ulist[$i] && $k <= $h )
            $k ++;
        while( $flist[$h] > $ulist[$j] && $k <= $h )
            $h --;
    }
    return $nlist;
}

function pm_feed($ulist, $flist)
{
    $nlist = array();
    $stack = array();
    $data = array(0, count($ulist) - 1, 0, count($flist) - 1);

    while( $data && $data[0] <= $data[1] && $data[2] <= $data[3] )
    {
        if( $ulist[$data[1]] < $flist[$data[2]] || $ulist[$data[0]] > $flist[$data[3]] )
        {
            $data = array_pop($stack);
            continue;
        }

        if( $data[1] - $data[0] > 10 && $data[3] - $data[2] > 10 )
        {
            $umid = ($data[0] + $data[1]) / 2;
            while( $ulist[$umid] == $flist[$data[2]] && $data[2] < $data[3] )
            {
                array_push($nlist, $data[2]);
                $data[2] ++;
            }
            while( $ulist[$umid] == $flist[$data[3]] && $data[2] < $data[3] )
            {
                array_push($nlist, $data[3]);
                $data[3] --;
            }

            if( $ulist[$umid] < $flist[$data[2]] )
            {
                $data[0] = $umid + 1;
                continue;
            }
            else if( $ulist[$umid] > $flist[$data[3]] )
            {
                $data[1] = $umid - 1;
                continue;
            }

            $fmid = _asc_bin_search($flist, $ulist[$umid], $data[2], $data[3]);
            array_push($stack, array($data[0], $umid - 1, $data[2], $fmid));
            $data = array($umid, $data[1], $fmid + 1, $data[3]);
            continue;
        }
        else if( $data [3] - $data[2] > 10 )
        {
            $n = feed_small_follows($ulist, $flist, $data);
            $nlist = array_merge($nlist, $n);
        }
        else if( $data [1] - $data[0] > 10 )
        {
            $n = feed_small_news($ulist, $flist, $data);
            $nlist = array_merge($nlist, $n);
        }
        else
        {
            $n = _feed_small_core($ulist, $flist, $data);
            $nlist = array_merge($nlist, $n);
        }

        $data = array_pop($stack);
    }
    return $nlist;
}

/**
 * 处理信息的时间线
 * 用户登录后，将信息拉过来，步骤如下：
 * 1、查询上次拉信息的时间点，
 * 2、从上次查询的时间点，到现在，一次性最多将400条信息ID加进feed表中
 * 3、若上次处理的时间较远，本次查询到超过400条，
 * 则将以前的数据删除掉，以前的数据已经过时了
 */
class DisFeedCtrl extends DisFeedData
{
//    static $_interval = 300;
    var $mem_flows  = array();
    var $follow_ids = array();
    var $flag = 0;
    var $his_read;      // 正发送到客户端的信息流的最小ID
    var $feedtime;

    function  __construct($user_id)
    {
        parent::__construct($user_id);
    }

    static function read_ctrler($user_id)
    {
        if( !isset ($_SESSION['feed']) || $_SESSION['feed'] == null )
        {
            $feed = new DisFeedCtrl($user_id);
            $feed->start_feed(40);
        }
        else
            $feed = unserialize($_SESSION['feed']);
        return $feed;
    }

    static function save_ctrler($feed)
    {
        $_SESSION['feed'] = serialize($feed);
    }

    function start_feed($count = 40)
    {
        if( !$this->user_id )
            throw new DisException("Feed对象没有初始化");

        $this->flag = (int)(time() / DisConfigAttr::$intervals['five']);
        $max = $this->get_max_feed();
        $lasttime = $max ? $max['flow_time'] : 0;

        $len = $this->feed_hist_mails($lasttime, $this->flag * DisConfigAttr::$intervals['five'], $count);
        if( $len >= $count )
            parent::drop_outtime($lasttime);
    }

    function push_flow($flow_id)
    {
        array_unshift($this->follow_ids, $flow_id);
        $flow = DisNoteFlowCtrl::get_data($flow_id);
        if( $flow )
            $this->insert($flow['ID'], $flow['flow_time']);
    }

    function drop_flow($flow_id)
    {
        $_idx = array_search($flow_id, $this->follow_ids);
        $idx = $_idx ? $_idx : 0;
        if( $idx >= 0 &&  $this->follow_ids[$idx] == $flow_id )
            array_splice ($this->follow_ids, $idx, 1);
        parent::drop_flow($flow_id);
    }

    function list_flow_ids($start = 0, $count = 100)
    {
        $ids = $this->_list_flow_ids($start, $count);
        if( count($ids) < $count )
        {
            $this->feed_hist_mails(0, $this->feedtime, $count);
            $ids = $this->_list_flow_ids($start, $count);
        }
        return $ids;
    }

    protected function _list_flow_ids($start, $count = 100)
    {
        if( !$this->user_id )
            throw new DisParamException('对象没有初始化！');
        $len = count($this->follow_ids);

        if( $len < $start + $count )
        {
            if( $this->his_read == 0 )
            {
                $this->follow_ids = array();
                $flows = parent::top_follows($start + $count);
            }
            else
                $flows = parent::list_follows($this->his_read, $start + $count - $len);

            $_len = count($flows);
            if( $len > 0 )
                $this->his_read = $flows[$_len - 1]['flow_id'];
            else
                $this->his_read = 0;

            for( $i = 0; $i < $_len; $i ++ )
                array_push($this->follow_ids, $flows[$i]['flow_id']);
            $len = count($this->follow_ids);
        }

        return list_slice($this->follow_ids, $start, $count);
    }

    protected function feed_hist_mails($start, $end, $count)
    {
        $len = parent::feed_hist_mails($start, $end, $count);
        $min = $this->get_min_feed();
        $this->feedtime = $min ? $min['flow_time'] : 0;
        return $len;
    }

    /**
     * Feed用户订阅的channel里公开发布的信息
     * @param integer $user_id 用户ID
     * @return array 新跟踪的信息流ID
     */
    protected function _feed_mail($flag)
    {
        // 获取最近一段时间，系统产生的所有信息
        $notes = DisFeedVectorCache::get_notes($flag);
        if( !$notes )
        {
            $start = $flag * DisConfigAttr::$intervals['five'];
            $end = $start + DisConfigAttr::$intervals['five'];
            // 从数据库读取数据=> $flows
            $flows = parent::load_flows($start, $end);

            $notes['flow_ids'] = chunk_array($flows, 'ID');
            $notes['channel_ids'] = chunk_array($flows, 'channel_id');
            $notes['user_ids'] = chunk_array($flows, 'user_id');
            $notes['editor'] = $this->user_id;

            DisFeedVectorCache::set_notes($flag, $notes);
        }

        if( !$notes || !$notes['editor'] )
            return null;
        $this->flag = $flag;
        if( count($notes['flow_ids']) == 0 )
            return null;

        $cu = new DisChanUserCtrl($this->user_id);
        $clist = $cu->list_subscribed_asc_ids();
        $nlist = pm_feed($clist, $notes['channel_ids']);

        $feed = array();
        $len = count( $nlist );
        for( $i = 0; $i < $len; $i ++ )
        {
            if( $notes['user_ids'][$nlist[$i]] == $this->user_id )
                continue;
            array_push($feed, $notes['flow_ids'][$nlist[$i]]);
        }

        return $feed;
    }

    function feed_new()
    {
        $flag = (int)(time() / DisConfigAttr::$intervals['five']);
        for( $i = $this->flag + 1; $i < $flag; $i ++ )
        {
            $feed = $this->_feed_mail($i);
            if( $feed )
                $this->mem_flows = array_merge($feed, $this->mem_flows);
        }
        return $this->mem_flows;
    }

    function read_feed()
    {
        if( !$this->user_id )
            throw new DisParamException('对象没有初始化！');
        $flow_ids = array();
        $len = count($this->mem_flows);

        for( $i = 0; $i < $len; $i ++ )
        {
            $flow = DisNoteFlowCtrl::get_data($this->mem_flows[$i]);
            if( !$flow )
                continue;
            $this->insert($flow['ID'], $flow['flow_time']);
            array_push($flow_ids, $this->mem_flows[$i]);
        }

        if( count($flow_ids) > 0 )
            $this->follow_ids = array_merge($flow_ids, $this->follow_ids);
        $this->mem_flows = array();
    }

    function subscribe($channel_id)
    {
        $len = parent::subscribe($channel_id, $this->feedtime, $this->flag * DisConfigAttr::$intervals['five']);
        if( $len > 0 )
        {
            $this->his_read = 0;
            $this->follow_ids = array();
        }
    }

    function cancel_subscribe($channel_id)
    {
        $len = parent::cancel_subscribe($channel_id);
        if( $len > 0 )
        {
            $this->his_read = 0;
            $this->follow_ids = array();
        }
    }
}
?>
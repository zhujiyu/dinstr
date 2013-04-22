<?php
/**
 * @package: DIS.CTRL
 * @file   : DisValueCtrl.class.php
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

    /**
     * 调整最大根堆
     * @param array $flows 堆的存放区
     * @param int $index 当前要调整的小堆根的下标
     * @param int $len 堆的总大小
     */
function heap(&$flows, $index, $len)
{
    $i0 = $index;
    $p0 = $flows[$i0][param];

    $i1 = $index * 2;
    if( $i1 < $len )
    {
        $p1 = $flows[$i1][param];
            // $p0的key小于$p1的key，或者两个节点的key相等，但是前者的rank小，
            // 或者前者已经空了，后者队列还没空
        if( $p0[key] < $p1[key]
            || ($p0[key] == $p1[key] && $p0[rank] < $p1[rank])
            || ($p0[index] >= $p0[length] && $p1[index] < $p1[length]) )
        {
            $max_index = $i1;
            $max_param = $p1;
        }
        else
        {
            $max_index = $i0;
            $max_param = $p0;
        }
    }

    $i2 = $index * 2 + 1;
    if( $i2 < $len )
    {
        $p2 = $flows[$i2][param];
            // $max_param是$p0和$p1中的最大者
            // $max_param的key小于$p2的key，或者两个节点的key相等，但是前者的rank小，
            // 或者前者已经空了，后者队列还没空
        if( $max_param[key] < $p2[key]
            || ($max_param[key] == $p2[key] && $max_param[rank] < $p2[rank])
            || ($max_param[index] >= $max_param[length] && $p2[index] < $p2[length]) )
        {
            $max_index = $i2;
            $max_param = $p2;
        }
    }

    if( $max_index > $i0 )
    {
        $flows[0] = $flows[$i0];
        $flows[$i0] = $flows[$max_index];
        $flows[$max_index] = $flows[0];

        if( $max_index * 2 < $len )
            heap($flows, $max_index, $len);
    }
}

class DisValueCtrl extends DisObject
{
    var $flag = 0;
//    var $inited = 0;
    var $period = 86400;
    var $vu_flows = null;
    var $flow_ids = null;

    function __construct($period)
    {
        if( !array_key_exists($period, DisConfigAttr::$periods) )
            $period = '1d';
        $this->period = $period;
    }

    static function read_ctrler($period)
    {
        if( isset($_SESSION['values']) && $_SESSION['values'] )
        {
            $values = unserialize($_SESSION['values']);
            if( !isset ($values[$period]) || $values[$period] == null )
                $value = new DisValueCtrl($period);
            else
                $value = $values[$period];
        }
        else
        {
            $value = new DisValueCtrl($period);
        }
        return $value;
    }

    static function save_ctrler($value)
    {
        if( isset ($_SESSION['values']) && $_SESSION['values'] )
            $values = unserialize($_SESSION['values']);
        else
            $values = array();

        $values[$value->period] = $value;
        $_SESSION['values'] = serialize($values);
    }

    function init($chanids, $weights)
    {
        $flag = (int)(time() / DisConfigAttr::$intervals['quarter']);
        if( $flag <= $this->flag )
            return;
//        if( $this->inited == 1 )
//            return;
//        $this->inited = 1;

        $this->flag = $flag;
        $this->flow_ids = array();
        $this->vu_flows = array();
        array_push($this->vu_flows, array());

        $cl_len = count($chanids);
        for( $i = 0; $i < $cl_len; $i ++ )
        {
            if( !is_int($chanids[$i]) && !is_string($chanids[$i]) || $chanids[$i] == '' )
                continue;
            $vflows = DisChannelCtrl::list_value_flows($chanids[$i], $this->flag, $this->period);
            $chanid = $vflows[info][id];
            $vflows['param'] = array
            (
                'weight'=>$weights[ $chanid ][weight],
                'rank'=>$weights[ $chanid ][rank],
                'key'=>$vflows[weights][0] * $weights[ $chanid ][weight],
                'index'=>0,
                'length'=>count($vflows[flow_ids])
            );
            array_push($this->vu_flows, $vflows);
        }

        $heap_len = count($this->vu_flows);
        for( $i = (int)($heap_len / 2); $i > 0; $i -- )
            heap($this->vu_flows, $i, $heap_len);
    }

    protected function read($count)
    {
        $flow_ids = array();
        $len = count($this->vu_flows);

        for( $i = 0; $i < $count; $i ++ )
        {
            $param = $this->vu_flows[1]['param'];
            if( $param['index'] >= $param['length'] )
                break;
            array_push($flow_ids, $this->vu_flows[1]['flow_ids'][$param['index']]);

            $param[index] = (int)$param[index] + 1;
            if( $param[index] < $param[length] )
                $param[key] = $param[weight] * $this->vu_flows[1]['weights'][$param[index]];
            else
                $param[key] = 0;

            $this->vu_flows[1]['param'] = $param;
            heap($this->vu_flows, 1, $len);
        }

        return $flow_ids;
    }

    function read_value_ids($page = 0, $count = 20)
    {
        $left = $page * $count;
        $hope = $left + $count;
        $have = count($this->flow_ids);

        if( $have < $hope )
        {
            $flow_ids = $this->read($hope - $have);
            $this->flow_ids = array_merge($this->flow_ids, $flow_ids);
            $have = count($this->flow_ids);
        }
        return list_slice($this->flow_ids, $left, $count);
    }

    protected function drop_flow($flow_id)
    {
        $idx = array_search($flow_id, $this->flow_ids);
        if( $idx && $this->flow_ids[$idx] == $flow_id )
            array_splice($this->flow_ids, $idx, 1);
    }

    function restart()
    {
        $this->flag = 0;
        $this->flow_ids = array();
        $this->vu_flows = array();
    }
}
?>
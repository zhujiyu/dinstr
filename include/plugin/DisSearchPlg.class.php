<?php
/**
 * @package : DIS.PLUGIN
 * @file    : DisSearchPlg.class.php
 * @abstract  : 搜索
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

/**
 * utf-8 字符串截取函数
 * @param string $srcstr 要截取的字符串
 * @param integer $length 截取长度（文字个数），默认100
 * @param integer $start 开始截取地方，默认0
 * @param string $endstr 截取后的字符串末尾字符串，默认是 “…”
 * @return string 截取的字符串结果
 */
function imSubstr($srcstr, $length = 100, $start = 0, $endstr = '…')
{
    $strlen = strlen($srcstr); //字符串的字节数

    for( $n = 0, $res = ''; $n < $length && $start <= $strlen; )
    {
        $temp = substr($srcstr, $start, 1);
        $ascn = Ord($temp); //ascii码

        if( $ascn >= 224 )
        {
            $res .= substr($srcstr, $start, 3);
            $start = $start + 3;
            $n ++;
        }
        else if( $ascn >= 192 )
        {
            $res .= substr($srcstr, $start, 2);
            $start = $start + 2;
            $n ++;
        }
        else
        {
            $res .= substr($srcstr, $start, 1);
            $start = $start + 1;
            $n = $n + 0.5;
        }
    }

    if( $start < $strlen )
        $res .= $endstr;

    return $res;
}

class DisSearchPlg extends SphinxClient
{
    // 构造函数
    function __construct($host = "localhost", $port = 9312, $mode = SPH_MATCH_ALL)
    {
        parent::SphinxClient();
        parent::SetServer ( $host, $port );
        parent::SetConnectTimeout ( 1 );
        parent::SetArrayResult ( true );
        parent::SetWeights ( array ( 100, 1 ) );
        parent::SetMatchMode ( $mode );
    }

    /**
     * 搜索频道
     * @param string $q 搜索的关键词
     * @param array $res 搜索结果
     * @return array 返回搜索结果
     */
    function Offices($q, $res = array(), $len = 5)
    {
        $search = $this->Query ( $q, "_office, _office_delta" );

        if( !$search || $search[total] < 1 || !$search[matches] || !is_array($search[matches]) )
            return $res;

        $matches = $search[matches];
        $count = count($matches);

        for( $i = 0; $i < $count && $i < $len; $i ++ )
        {
            $off = soOfficeControl::get_data($matches[$i]['id']);
            $data = array('type'=>'office', 'id'=>$off['id'], 'label'=>$off['name'],
                'desc'=>strip_tags($off['description']));
            $data[office] = $off;
            array_push($res, $data);
        }

        return $res;
    }

    function Wishes($q, $res = array(), $len = 5)
    {
        $search = $this->Query ( $q, "_wishes, _wishes_delta" );

        if( !$search || $search[total] < 1 || !$search[matches] || !is_array($search[matches]) )
            return $res;

        $matches = $search[matches];
        $count = count($matches);

        for( $i = 0; $i < $count && $i < $len; $i ++ )
        {
            $wish = soWishControl::get_data($matches[$i]['id']);
            $data = array('type'=>'wish', 'id'=>$wish['id'], 'label'=>imSubstr($wish['content'], 30),
                'desc'=>($wish['follow_num'].'关注&nbsp;'.$wish['approved_num'].'赞同'));
            $data[wish] = $wish;
            array_push($res, $data);
        }

        return $res;
    }

    function News($q, $res = array(), $len = 5)
    {
        $search = $this->Query ( $q, "_news, _news_delta" );

        if( !$search || $search[total] < 1 || !$search[matches] || !is_array($search[matches]) )
            return $res;

        $matches = $search[matches];
        $count = count($matches);

        for( $i = 0, $j = 0; $i < $count && $j < $len; $i ++ )
        {
            $news = soNewsControl::get_data($matches[$i]['id']);
            if( $news[root] == 0 )
                continue;

            try
            {
                $wish = soWishControl::get_data($news['root']);
            }
            catch (soException $ex)
            {
                continue;
            }

            $data = array('type'=>'news', 'id'=>$wish['id'], 'label'=>imSubstr($news['content'], 30),
                'desc'=>(imSubstr($wish['content'], 20).'&nbsp;'.$wish['follow_num'].'关注&nbsp;'.$wish['approved_num'].'赞同'));

            $data[news] = $news;
            $data[wish] = $wish;
            array_push($res, $data);
            $j ++;
        }

        return $res;
    }
}
?>

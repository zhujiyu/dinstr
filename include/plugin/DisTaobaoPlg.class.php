<?php
/**
 * @file : DisTaobaoPlg.class.php
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 DIS 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisTaobaoPlg extends DisObject
{
    var $key;
    var $secret;
    var $sign_method;
    var $nick;
    var $param;

    function  __construct()
    {
        $this->key = DisConfigAttr::$taobaoAPI[key];
        $this->secret = DisConfigAttr::$taobaoAPI[secret];
        $this->sign_method = DisConfigAttr::$taobaoAPI[signMethod];
        $this->nick = DisConfigAttr::$taobaoAPI[nick];

        $this->param = array
        (
            'v' => '2.0',
            'nick'  => $this->nick,
            'app_key'   => $this->key,
            'format'    => 'json',
            'timestamp' => date('Y-m-d H:i:s'),
            'fields'    => 'iid,title,nick,pic_url,price,click_url,num_iid',
        //    'fields'  => 'click_url,shop_click_url,seller_credit_score,iid,detail_url,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,auto_repost,approve_status,postage_id,product_id,auction_point,property_alias,item_imgs,prop_imgs,skus,outer_id,is_virtual,is_taobao,is_ex,videos,is_3D,score,volume,one_station,postage_id',
            'page_size' => 24,
            'sign_method' => $this->sign_method //'HmacMD5'
        );
    }

    /**
     * 添加参数
     * @param array $paramArr 参数列表
     * @return array 返回参数列表
     */
    function mergeParam($paramArr)
    {
        foreach ( $paramArr as $name => $value )
            $this->param[$name] = $value;
        return $this->param;
    }

    // 签名函数
    function createSign()
    {
        $paramArr = $this->param;
        ksort($paramArr);
        $sign = $this->secret;

        foreach ($paramArr as $key => $val)
        {
            if ($key !='' && $val !='')
            {
                $sign .= $key.$val;
            }
        }

        if ( !$paramArr['sign_method'] || $paramArr['sign_method'] == 'HmacMD5' )
            $sign = strtoupper(md5($sign));  //Hmac方式
        else
            $sign = strtoupper(md5($sign.$this->secret)); //Md5方式
        return $sign;
    }

    // 组参函数
    function createStrParam()
    {
        $paramArr = $this->param;
        ksort($paramArr);
        $strParam = '';

        foreach ($paramArr as $key => $val)
        {
            if ($key != '' && $val !='')
            {
                $strParam .= $key.'='.urlencode($val).'&';
            }
        }
        return $strParam;
    }

    // 组织缓存参数
    function createCacheParam($paramArr)
    {
        $strParam = '';
        foreach ($paramArr as $key => $val)
        {
            if ($key != '' && $val !='' && $key != 'timestamp'
                && $key != 'app_key' && $key != 'nick')
            {
                $strParam .= $key.'='.urlencode($val).'&';
            }
        }
        return $strParam;
    }

    /**
     * 可能是用来验证回调的函数
     * @param string $appKey 用户key
     * @param string $appSecret 密码
     * @return boolean
     */
    function taobaoSessionKey($appKey, $appSecret)
    {
        if ( !$_GET['top_appkey'] || $_GET['top_appkey'] != $appKey ) //'12135593'
            throw new DisParamException('用户key错误');

        $parameters = $_GET['top_parameters'];
        $sign = $_GET['top_appkey'].$_GET['top_parameters'].$_GET['top_session'].$appSecret;
        $sign = base64_encode(md5($sign, true));
        if ( $sign != $_GET['top_sign'] )
            throw new DisParamException('无效的签名');

        $parameters = array();
        parse_str(base64_decode($_GET['top_parameters']), $parameters);

        $now = time();
        $ts = $parameters['ts'] / 1000;
        if ( $ts > ( $now + 60 * 10 ) || $now > ( $ts + 60 * 30 ) )
            throw new DisParamException('请求超时');
            //exit ("request out of date.");

        $_SESSION['sessionKey'] = $_GET['top_session'];
        $_SESSION['visitorNick'] = $parameters['visitor_nick'];
    }
}
?>
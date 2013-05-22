<?php
/**
 * @package: DIS.DATA
 * @file   : DisGoodData.class.php
 * 网络商品
 *
 * @author    : 朱继玉<zhuhz82@126.com>
 * @Copyright : 2013 有向信息流
 * @Date      : 2013-04-16
 * @encoding  : UTF-8
 * @version   : 1.0.0
 */
if( !defined('IN_DIS') )
    exit('Access Denied!');

class DisGoodData extends DisDBTable
{
    // 构造函数
    function __construct($good_id = 0)
    {
        $this->table = "goods";
        parent::__construct($good_id);
    }

    function init($good_id, $slt = 'ID, user_id, source, num_iid, title, price, price_url,
        pic_url, click_url, shop, shop_url, item_location, quote, update_time')
    {
        return parent::init($good_id, $slt);
    }

    function load($iid, $source = 'taobao')
    {
        if( $this->_check_param('num_iid', $iid) != DIS_SUCCEEDED
            || $this->_check_param('source', $source) != DIS_SUCCEEDED )
            throw new DisParamException('参数格式不正确！');

        return $this->select("`num_iid` = '$iid' and `source` = '$source' ",
            "ID, user_id, source, num_iid, title, price, price_url, shop, pic_url, click_url,
            shop_url, item_location, quote, update_time");
    }

    function _check_param($name, $value)
    {
        switch($name)
        {
            case 'user_id' :
                if( !uid_check($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'num_iid' :
                if( !is_numeric($value) && !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'source' :
                if( !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'title' :
            case 'shop' :
            case 'item_location' :
            case 'desc' :
                if( !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'pic_url' :
            case 'click_url' :
            case 'price_url' :
            case 'shop_url' :
                if( !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'quote' :
            case 'trade_num' :
            case 'click_num' :
                if( !is_integer($value) )
                    return err(DIS_ERR_PARAM);
                break;
            case 'price' :
                if( !is_numeric($value) && !is_string($value) )
                    return err(DIS_ERR_PARAM);
                break;
            default : return err(DIS_ERR_PARAM);
        }
        return err(DIS_SUCCEEDED);
    }

    protected function _check_num_param($param)
    {
        return in_array($param, array('quote', 'trade_num', 'click_num'));
    }

    function insert($user_id, $source, $num_iid, $title, $price, $click_url, $pic_url,
        $shop = '', $shop_url = '', $item_location = '')
    {
        if ( !$user_id || !$source || !$num_iid )
            throw new DisParamException('参数格式不正确！');

        $reg = '/^\d+\.?\d*$/';
        if ( preg_match($reg, $price) )
        {
            $price = $price;
            $price_url = '';
        }
        else
        {
            $price_url = $price;
            $price = 0;
        }

        return parent::insert(array('user_id'=>$user_id, 'source'=>$source,
            'num_iid'=>$num_iid, 'title'=>$title, 'price'=>$price, 'price_url'=>$price_url,
            'shop'=>$shop, 'click_url'=>$click_url, 'pic_url'=>$pic_url,
            'item_location'=>$item_location, 'shop_url'=>$shop_url));
    }

    function click()
    {
        return $this->increase('click_num', 1);
    }

    function trade()
    {
        return $this->increase('trade_num', 1);
    }
}
?>
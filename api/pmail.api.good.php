<?php
/**
 * @package: PMAIL.INTE.API
 * @file   : pmail.api.good.php
 * 商品API
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.11
 */
require_once '../common.inc.php';

$p = $_GET['p'] ? $_GET['p']: $_POST['p'];
$src = $_GET['src'] ? $_GET['src']: $_POST['src'];

function parse_taobao($id)
{
    $tb = new DisTaobaoPlg();
    $tb->mergeParam(array('method'=>'taobao.item.get', 'num_iid'=>$id));
    $sign = $tb->createSign();
    $strParam = $tb->createStrParam();
    $strParam .= 'sign='.$sign;

    //访问服务
    $url = DisConfigAttr::$taobaoAPI[url].$strParam;
        //header("Location: ".$url); exit;
    $fileContent = file_get_contents($url);
    $fileResult = json_decode($fileContent, true);
    $itemsResult = $fileResult[item_get_response];

    if( $itemsResult[code] )
        throw new DisException($itemsResult[sub_msg]);
    return $itemsResult[item];
}

ob_start();
try
{
    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0 || !DisUserCtrl::check_inline($_SESSION['userId']) )
        throw new DisException("没有登录！");
    $good_id = 0;

    if( $p == 'parse' )
    {
        $good = new DisGoodCtrl();
        $good->load($_GET['num_iid'], $_GET['src']);

        if( $good->ID == 0 )
        {
            if( $src == 'taobao' || $src == 'tmall' )
                $_tmall = parse_taobao($_GET['num_iid']);
            else
                throw new DisException('无效的类型');

            $good->insert((int)$_SESSION['userId'], $_GET['src'], $_tmall[num_iid],
                    $_tmall[title], $_tmall[price], $_GET[url], $_tmall[pic_url], $_tmall[nick]);
        }

        $val[good] = $good->info();
        $val[good][ID] = $good->ID;
        $good_id = $good->ID;
    }
    else if( $p == 'save' )
    {
        // 返回的是soGoodControl对象
        $good = pmApiGood::Save();
//        $good_code = '<div class="so-good" id="'.$good->ID.'">商品保存成功!</div>';
    }
    else
        echo "参数无效";
}
catch (DisException $ex)
{
    $ex->trace_stack();
    echo $ex->getMessage();
}

$err = ob_get_contents();
ob_end_clean();

$val['err'] = $err;
$val['id'] = $good_id;
echo json_encode($val);
?>
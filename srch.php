<?php
/**
 * PMAIL项目 php文件 v1.9.25
 *
 * 会社页面
 * @Encoding  :   UTF-8
 * @Author    :   zhujiyu , zhujiyu@139.com
 * @Date      :   2011-10-5 3:32:53
 * @Copyright :   2011 社交化协同服务办公系统项目
 */
require_once 'common.inc.php';

ob_start();
try
{
    $item = $_REQUEST['item'] ? $_REQUEST['item'] : 'channel';
    assert( $item === 'channel' || $item === 'theme' || $item === 'mail' );
    $keyword = $_GET['keyword'] ? $_GET['keyword']: $_POST['keyword'];

    $res = array();
    $search = new DisSearchPlg();

    if( $keyword )
    {
        if ( $item == 'office' )
            $res = $search->Offices($keyword, $res, 20);
        else if ( $item == 'wish' )
            $res = $search->Wishes($keyword, $res, 20);
        else
            $res = $search->News($keyword, $res, 20);
    }

    $gSmarty->assign("searchs", $res);
}
catch (soException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$gSmarty->assign("err", $err);
$gSmarty->assign("keyword", $keyword);
//$gSmarty->display("pages/$file");

if( $item == 'office' )
{
    $gSmarty->display("pages/pmail.search.channel.tpl");
}
elseif ( $item == 'wish' )
{
    $gSmarty->display("pages/pmail.search.theme.tpl");
}
else
{
    $gSmarty->display("pages/pmail.search.mail.tpl");
}
?>
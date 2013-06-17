<?php
/**
 * @package: PMAIL.INTE.API
 * @file   : pmail.api.file.php
 * 文件API
 * @author    : zhujiyu , zhujiyu@139.com
 * @Copyright : 2012 公众邮件网
 * @Date      : 2012-4-11
 * @encoding  : UTF-8
 * @version   : 2.4.11
 */
require_once '../common.inc.php';

ob_start();
try
{
    $p = $_GET['p'] ? $_GET['p']: $_POST['p'];
    $val['ID'] = 0;

    if( !isset($_SESSION['userId']) || $_SESSION['userId'] == 0
            || !DisUserCtrl::check_inline($_SESSION['userId']) )
    {
        if( $p == 'upload' )
            throw new DisException("没有登录！");
        $user_id = 0;
    }
    else
        $user_id = (int)$_SESSION['userId'];

    if( $p == 'upload' )
    {
        $file = $_FILES['pm-upload-img'];
        $photo = new DisPhotoCtrl();
        $photo->upload($file, $user_id, '..');

        $val['ID'] = $photo->ID;
        $val['photo'] = $photo->info();
        $val['oldName'] = $file['name'];
    }
    else if( $p == 'adjust' )
    {
        $photo = new DisPhotoCtrl((int)$_GET['id']);
        $photo->to_avatar($_GET['src'], "../");
        $val['ID'] = $photo->ID;
        $val['photo'] = $photo->info();
    }
    else if( $p == 'delete' )
    {
        $photo = new DisPhotoCtrl((int)$_GET['id']);
        $val['ID'] = $photo->ID;
        $val['photo'] = $photo->info();
        $photo->remove('..');
    }
    else if( $p == 'disp' )
    {
        $photo = new DisPhotoCtrl((int)$_GET['id']);
        echo "<img src=\"../".$photo->attr('big')."\">";
        echo "<img src=\"../".$photo->attr('small')."\">";
        exit;
    }
    else if( $p == 'move' )
    {
        $url = "http://ww4.sinaimg.cn/bmiddle/698b48d3jw1dychl3seluj.jpg";
        $photo = new DisPhotoCtrl();
        $photo->move($url, '..');
        echo '<p><img src="'.$photo->name.'"></p>';
        exit;
    }
    else
        echo "无效的操作类型";
}
catch (DisException $ex)
{
    echo $ex->getMessage();
}

$val['error'] = ob_get_contents();
ob_end_clean();

//echo $err;
//pmObject::print_array($val);
echo json_encode($val);
?>
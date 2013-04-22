<?php
/**
 * Encoding     :   UTF-8
 * Author       :   zhujiyu , zhujiyu@139.com
 * Created on   :   2011-11-21 2:16:39
 * Copyright    :   2011 社交化协同服务办公系统项目
 */
require_once '../common.inc.php';

ob_start();
try
{
    $q = $_GET['term'] ? $_GET['term'] : $_POST['term'];
    $search = new DisSearchPlg();
    $res1 = $search->Offices($q);
    $res2 = $search->Wishes($q, $res1);
    $res3 = $search->News($q, $res2);
}
catch (soException $ex)
{
    $ex->trace_stack();
}

$err = ob_get_contents();
ob_end_clean();

$val['err'] = $err;
$val['res'] = $res3;
echo json_encode($val);
?>

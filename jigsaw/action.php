<?php
ini_set('session.gc_maxlifetime', 604800);
ini_set('session.cookie_lifetime', 604800);
session_start();
header('Access-Control-Allow-Origin:*');

include 'vendor/autoload.php';
include 'core/Configs/app.php';

try {

    $result['data'] = Jigsaw\Libraries\Func::exec();
    $result['ret'] = 1;
} catch (Exception $ex) {
    if ($ex->getCode() == Jigsaw\Libraries\Exception::UNLOGIN_CODE) {
        $result['ret'] = 2;
    } else {
        $result['ret'] = 0;
    }
    if ($ex->getCode() == Jigsaw\Libraries\Exception::SYS_CODE) {
        //         $result['msg'] = '系统异常，请重试';
        $result['msg'] = $ex->getMessage();
    } else {
        $result['msg'] = $ex->getMessage();
    }
}

$cb = $_REQUEST['callback'];
if (empty($cb)) {
    echo json_encode($result);
} else {
	echo $cb.'('.json_encode($result).')';
}
exit;
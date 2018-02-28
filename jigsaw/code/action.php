<?php
ini_set('session.gc_maxlifetime', 604800);
ini_set('session.cookie_lifetime', 604800);
session_start();
header('Access-Control-Allow-Origin:*');

include 'vendor/autoload.php';
include 'core/Configs/app.php';

if (ISDEBUG) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

try {
    $result['data'] = Projectname\Libraries\Func::exec();
    $result['ret'] = 1;
} catch (Exception $ex) {
    if ($ex->getCode() == Projectname\Libraries\Exception::UNLOGIN_CODE) {
        $result['ret'] = 2;
    } else {
        $result['ret'] = 0;
    }
    if ($ex->getCode() == Projectname\Libraries\Exception::SYS_CODE) {
        if (ISDEBUG) {
            $result['msg'] = $ex->getMessage();
        } else {
            $result['msg'] = '系统异常，请重试';
        }
    } else {
        $result['msg'] = $ex->getMessage();
    }
}

$cb = isset($_REQUEST['callback']) ? $_REQUEST['callback'] : 0;
if (empty($cb)) {
    echo json_encode($result);
} else {
	echo $cb.'('.json_encode($result).')';
}
exit;
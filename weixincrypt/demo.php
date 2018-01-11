<?php

include_once "wxBizMsgCrypt.php";

// 第三方发送消息给公众平台
$encodingAesKey = "7e8d34fcdb9419312bae660e5cf501e4b4081e60b3a";
$token = "gwt@2012";
$appId = "wxe125e58dd6f3f43b";


$pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);


$msg_sign = '3f81eac5cb114042deb7ee97f031ea5ad4ba639f';
//$msg_sign = '6ce18d39a3af9fff07c605cc6dfafab93ac1413f';

$timeStamp = '1512121108';
$nonce = '1876070259';
$from_xml = '<xml><ToUserName><![CDATA[wxe125e58dd6f3f43b]]></ToUserName><Encrypt><![CDATA[3oq13hPhwbnjG20qokBIZxVdk3cEmw352RWsh0p7srkCfPOYB8ua5gvY7RnI62Xn5fyZGktyzwYiCdyK7pjO5HC0kYY8hI7KrCwT4yXoUOY30JgNiA4lhn7/wejbqOPp+Od9oMMFM9M6ArJQPaCt/lstHOFO0k3VIbBUYCdQN3Wg0p/Y0ePaG0F9kK86CKL4hJbrb3Mcx3TktbknuCOjrwzv5knAzZHVZp3qR+S9Eoz7ZHPQMHWOj7vCfXzOTzjjemlCUo5zxDRPQosV4MyYeKbI4itzD7Z4QIigCO43MgjcUtQb4nHeoZiAV20vyB2gHRbiRWR4Sn4yoNlfWeS6EFyhwhkiKdxhP5G8+vLzAsF8/0LruGkbuirWB3FAvOLaQKx8JpzUINTA83LaWspJdEkhsSWF8rE7NYt6LgUIdXnLR42D+ryvMpg3MQN7v1tkgjDW3PQtT5J3oHX94U5T9g==]]></Encrypt></xml>';


// 第三方收到公众号平台发送的消息
$msg = '';
$errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
if ($errCode == 0) {
	print("解密后: " . $msg . "\n");
} else {
	print($errCode . "\n");
}

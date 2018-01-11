<?php
// session_start();
ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);
// $client = new SoapClient(null, array(
//       'location' => "http://ljqian.local.com/soap/server.php",
//       'uri'      => "http://ljqian.local.com/soap/server.php",
//       'trace'    => 1 ));

// header("Content-Type:image/jpg");
// $doimg = $client->__getFunctions();
// var_dump($doimg);exit;
// $_SESSION['vcode_t'] = strtolower($client->__soapCall("getCode", []));
// exit();


$client = new SoapClient(null, array(
    'location' => "http://ljqian.local.com/soap/server.php",
    'uri'      => "http://ljqian.local.com/soap/server.php",
    'trace'    => 1 ));

// header("Content-Type:image/jpg");
$ret = $client->__soapCall("doimg",array());
echo base64_decode($ret['imgsrc']);
echo $ret['code'];
exit;
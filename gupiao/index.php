<?php
header('Content-type:text/html;charset=utf-8');
//配置您申请的appkey
$appkey = "ff9544c12c79bd9827579ae60f6fd330";




//************1.沪深股市************
$url = "http://web.juhe.cn:8080/finance/stock/hs";
$params = array(
    "gid" => "sz002558",//股票编号，上海股市以sh开头，深圳股市以sz开头如：sh601009
    "key" => $appkey,//APP Key
);
$paramstring = http_build_query($params);
$content = juhecurl($url,$paramstring);
$result = json_decode($content,true);
if($result){
    if($result['error_code']=='0'){
        //print_r($result);
        $isincrease = (intval($result['result'][0]['data']['increase']) > 0) ? 1 : 0;
        $arr = [
            '股票名'=>$result['result'][0]['data']['name'],
            '股票代号'=>$result['result'][0]['data']['gid'],
            '股票值'=>$result['result'][0]['data']['nowPri'],
            '增长点数'=>$result['result'][0]['data']['increase'],
            '增长百分比'=>$result['result'][0]['data']['increPer'],
            '是否增长'=>$isincrease//增长为1，减少为0
        ];
        print_r($arr);
    }else{
        echo $result['error_code'].":".$result['reason'];
    }
}else{
    echo "请求失败";
}

function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}
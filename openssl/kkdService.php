<?php
include_once "Md5RSA.class.php";
class kkdService
{
    private $rsaPrivateKeyFilePath;

    /**
     * 快快贷接口
     *
     * @param string $uuid
     *
     * @return mixed
     */
    public function getKkdUrl($uuid)
    {
        $uuid = md5($uuid);
        $url = 'https://thirdtest.kkcredit.cn/#/wcIndex/init';
        $source = 'yinjia';
        $timestamp = time();

        //签名
        $data = $uuid . $source . $timestamp;
        $objMd5Rsa = new Md5RSA();
//         $objMd5Rsa->rsaPrivateKeyFilePath = __DIR__ . '/rsa_key/rsa_private_key.pem';
        $objMd5Rsa->rsaPrivateKeyFilePath = __DIR__ . '/lj_key/private_key.pem';
        $sign = $objMd5Rsa->sign($data);
        $params = [
            'uuid'      => $uuid, //用户唯一标识号，同一个用户用户标识相同，长度不超过40
            'source'    => $source, //渠道（固定值）
            'timestamp' => $timestamp,
            'signature' => $sign,
            'pageshow'  => 'true',
        ];
        $apiUrl = $url . '?' . http_build_query($params);
        return $apiUrl;
    }
}

$uuid = '23345';
$obj = new kkdService();
$url = $obj->getKkdUrl($uuid);
die($url);

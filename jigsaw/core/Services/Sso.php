<?php
namespace Jigsaw\Services;

use SoapClient;

class Sso extends Base
{
    private $client = null;
    
    public function __construct()
    {
        parent::__construct();
        
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        
        $this->client = new SoapClient(null, [
            'location' => $this->config->constant('sso_passport'),
            'uri' => $this->config->constant('sso_passport'),
            'encoding' => 'UTF-8',
            'trace' => 1
        ]);
    }
    
    /**
     * 验证帐号密码的正确性
     * @param unknown $username
     * @param unknown $passwd
     * @return mixed
     */
    public function ValidateAdOnlyByPasswd($username, $passwd)
    {
        $input = [
            'user_name' => $username,
            'passwd' => $passwd
        ];
        
        $ret = $this->client->__soapCall("ValidateAdOnlyByPasswd", $input);
        return $ret;
    }
    
    /**
     * 根据帐号返回用户信息
     * @param unknown $account
     * @return mixed
     */
    public function QueryAdUserInfoByName($account)
    {
        $input = [
            'user_name' => $account
        ];
        
        $ret = $this->client->__soapCall("QueryAdUserInfoByName", $input);
        return $ret;
    }    
}
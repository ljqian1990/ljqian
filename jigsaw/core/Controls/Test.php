<?php
/**
 * 用户管理类
 *
 * @author qianlijia <qianlijia@ztgame.com>
 * @copyright Copyright (c) 巨人网络 (http://www.ztgame.com)
 * @package core.library
 * @version 1.0.0
 */
namespace Jigsaw\Controls;

class Test extends Base
{
    public function testservice()
    {
        $ssoservice = $this->loadService('sso');
        $ret = $ssoservice->QueryAdUserInfoByName('qianlijia');
//         $ret = $ssoservice->ValidateAdOnlyByPasswd('qianlijia', 'qljQLJ1709');
        var_dump($ret);exit;
    }
    
    public function filesync()
    {
        $filesyncService = $this->loadService('filesync');
        $filesyncService->Sync();
    }
    
    public function cdnsync()
    {
        $sCdnsync = $this->loadService('cdnsync');
        $sCdnsync->Sync('http://jl.ztgame.com/js/jquery-1.11.0.min.js');
    }
}
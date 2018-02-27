<?php
/**
 * 提供cdn推送服务
 * @author qianlijia<qianlijia@ztgame.com>
 * @date 2017.09.22
 *
 */
namespace Jigsaw\Services;

class CdnSync extends Base
{
    /**      
     * @var 定义file文件类型推送的type值
     */
    const FILE_TYPE = 1;
    
    const DIR_TYPE = 0;

    public function Sync($fileurl, $type=self::FILE_TYPE)
    {
        if ($type != self::FILE_TYPE && $type != self::DIR_TYPE) {
            $this->exception->throwSystemException($this->config->error('CDNSYNC_TYPE_ERROR'));
        }
        
        $url = $this->config->constant('cdn_sapi_url');
        
        $params = ['type'=>$type, 'content'=>$fileurl];
        
        $ret = $this->curl->setUrl($url)
            ->setMethod('POST')
            ->setData($params)
            ->httpRequest();
        
        $ret = json_decode($ret, true);        
        return $ret;
    }
}
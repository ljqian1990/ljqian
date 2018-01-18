<?php


(new class{
    public $mpid=0;
    public $works=[];
    public $max_precess=1;
    public $new_index=0;
    
    private $starttime = '';
    private $endtime = '';
    
    private $errortime = 0;

    public function __construct(){
        $this->starttime = microtime(true);
        try {
            swoole_set_process_name(sprintf('php-ps:%s', 'master'));
            $this->mpid = posix_getpid();
            $this->run();
            $this->processWait();
        }catch (\Exception $e){
            die('ALL ERROR: '.$e->getMessage());
        }
    }

    public function run(){
        for ($i=0; $i < $this->max_precess; $i++) {
            $this->CreateProcess($i);
        }
    }

    public function CreateProcess($index=null){
        $process = new swoole_process(function(swoole_process $worker)use($index){
            if(is_null($index)){
                $index=$this->new_index;
                $this->new_index++;
            }            
            
            swoole_set_process_name(sprintf('php-ps:%s',$index));
            for ($j = 0; $j < 1; $j++) {
                
                $this->checkMpid($worker);
                
                try {
                    
                    $client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
                    $client->set([
                        'open_eof_check' => true,
                        'package_eof' => "\r\n\r\n",
                        'package_max_length' => 1024 * 1024 * 2,
                        'open_length_check'     => 1,
                        'package_length_type'   => 'N',
                        'package_length_offset' => 0,       //第N个字节是包长度的值
                        'package_body_offset'   => 4,       //第几个字节开始计算长度
                        'package_max_length'    => 2000000,  //协议最大长度
                    ]);
                    $client->on("connect", function(swoole_client $cli) {
                        $timestamp = microtime(true);
                        $rand = rand(0, 100000);
                        $header = <<<EOT
GET /mcenter/index.php?c=index&f=index HTTP/1.1
Cache-Control: no-cache
Pragma: no-cache
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,;q=0.8
Accept-Language: zh-CN,zh;q=0.9
User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36
Upgrade-Insecure-Requests: 1
Connection: keep-alive
Cookie:uniqid=1801121121198890857718; ref=0; ref_date=2018-01-12+11%3A21%3A19.113; ref_ip=180.168.126.183; UM_distinctid=160e8cbec68286-0518c187e246dd-5e183017-1fa400-160e8cbec69afc; CNZZDATA1258731590=886713682-1515732391-%7C1515732391; ucms_index_tabIndex=1; date=2018-01-16+15%3A46%3A05.1609; uniqdata=1515727279.1516083573.1516088765.18; ref_uniqdata=5196%2C1515727279.1516083573.1516088765.16; pttoken=dd4249ef0526caa18e8991d90e185057; hash=9f3480d02c26cf64506c062bdb1da300; admin=0; visit_who_xiala=2018-01-16+16%3A06%3A02; red_who_xiala=2018-01-16+09%3A49%3A47; _ga=GA1.2.272257974.1515801622; _gid=GA1.2.1655984654.1515974466; user_name=qianlijia; token=dd4249ef0526caa18e8991d90e185057
Host: act.superpopgames.com
EOT;
                        $cli->send("{$header}\r\n\r\n");
                    });
                    $client->on("receive", function(swoole_client $cli, $data){
                        echo $data;
                        if (strpos($data, 'body:ok') === false) {
                            $this->errortime++;
                            //echo "error\r\n";                            
                        } else {
                            //echo "ok\r\n";
                        }
                         $cli->close();
                    });
                    $client->on("error", function(swoole_client $cli){
                        echo "error\n";
                        
                    });
                    $client->on("close", function(swoole_client $cli){
                        //echo "Connection close\n";
                    });
                    $client->connect('222.73.154.140', 80);                                        
                }catch (Exception $ex) {
                    echo $ex->getMessage()."\r\n";
                }
            }
        }, false, 1);
        $pid=$process->start();
        $this->works[$index]=$pid;
        return $pid;
    }
    
    public function checkMpid(&$worker){
        if(!swoole_process::kill($this->mpid,0)){
            $worker->exit();
            // 这句提示,实际是看不到的.需要写到日志中
            echo "Master process exited, I [{$worker['pid']}] also quit\n";
        }
    }

    public function rebootProcess($ret){
        $pid=$ret['pid'];
        $index=array_search($pid, $this->works);
        if($index!==false){
            $index=intval($index);
            $new_pid=$this->CreateProcess($index);
            //echo "rebootProcess: {$index}={$new_pid} Done\n";
            return;
        }
        throw new \Exception('rebootProcess Error: no pid');
    }

    public function processWait(){
        while(1) {
            if(count($this->works)){
                $ret = swoole_process::wait();
                if ($ret) {
                    $this->endtime = microtime(true);
                    
                    echo $this->errortime."\r\n";
                    //echo 'use time:'.($this->endtime - $this->starttime);                    
                    #$this->rebootProcess($ret);
                }
            }else{
                break;
            }
        }
    }
});

class Curl
{

    /**
     *
     * @var curl对象实体
     */
    private $oCurl = null;

    /**
     *
     * @var 请求方式
     */
    private $method = 'GET';

    /**
     *
     * @var 被请求对象的url地址
     */
    private $url = '';

    /**
     *
     * @var 请求发起时需要传递给被请求对象的数据 以键值对形式存放
     *      $data = ['name'=>'qianlijia', 'email'=>'qianlijia@ztgame.com'];
     */
    private $data = [];
    
    /**
     *
     * @var 请求发起时需要传递给被请求对象的数据 以键值对形式存放
     *      $data = ['name'=>'qianlijia', 'email'=>'qianlijia@ztgame.com'];
     */
    private $jsondata = [];

    /**
     *
     * @var 请求发起时需要传递给被请求对象的头部信息 $header = ["POST ".$page." HTTP/1.0", "Content-type: text/xml;charset=\"utf-8\""];
     */
    private $header = [];

    /**
     *
     * @var 请求发起时需要传递给被请求对象的cookie值 $cookie = ['name'=>'qianlijia', 'email'=>'qianlijia@ztgame.com'];
     */
    private $cookie = [];

    /**
     *
     * @var 超时时间
     */
    private $timeout = 5;

    /**
     *
     * @var 请求链接超时时间
     */
    private $connecttimeout = 5;

    /**
     *
     * @var 是否返回头部信息
     */
    private $returnheader = false;

    /**
     *
     * @var 被允许的请求method方式
     */
    private $legalMethods = [
        'GET',
        'HEAD',
        'PUT',
        'POST',
        'TRACE',
        'OPTIONS',
        'DELETE'
    ];
    
    public function setMethod($method)
    {
        $method = strtoupper($method);
        if (! in_array($method, $this->legalMethods)) {
            throw new Exception(sprintf('method:%s不在允许的范围内', $method));
        }
        $this->method = $method;
        return $this;
    }

    public function setUrl($url)
    {
        if (! $this->isUrl($url)) {
            throw new Exception('url格式不正确');
        }
        $this->url = $url;
        return $this;
    }

    public function setData($data)
    {
        $data = (array) $data;
        $this->data = $data;
        return $this;
    }
    
    /**
     * post json格式的数据
     */
    public function setJsonData($jsondata)
    {
        $jsondata = json_encode($jsondata);
        $this->jsondata = $jsondata;
        return $this;
    }

    public function setHeader($header)
    {
        $header = (array) $header;
        $this->header = $header;
        return $this;
    }

    public function setCookie($cookie)
    {
        $cookiestr = '';
        if (! empty($cookie)) {
            $cookiestr_arr = [];
            foreach ($cookie as $key => $value) {
                $cookiestr_arr[] = $key . '=' . $value;
            }
            $cookiestr = implode('; ', $cookiestr_arr);
        }
        $this->cookie = $cookiestr;
        return $this;
    }

    public function setTimeout($timeout)
    {
        $timeout = (int) $timeout;
        $this->timeout = $timeout;
        return $this;
    }

    public function setConnecttimeout($connecttimeout)
    {
        $connecttimeout = (int) $connecttimeout;
        $this->connecttimeout = $connecttimeout;
        return $this;
    }

    public function setReturnheader($returnheader)
    {
        if (! is_bool($returnheader)) {
            throw new Exception('returnheader必须为boolean类型');
        }
        $this->returnheader = $returnheader;
        return $this;
    }

    public function httpRequest()
    {
        $this->oCurl = curl_init();
        
        $url = $this->getUrl();
        if (stripos($url, 'https://') !== false) {
            curl_setopt($this->oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($this->oCurl, CURLOPT_SSLVERSION, 1); // CURL_SSLVERSION_TLSv1
        }
        $url = $this->replaceHostByIp($url);
        curl_setopt($this->oCurl, CURLOPT_URL, $url);
        
        $returnheader = $this->getReturnheader();
        if ($returnheader) {
            curl_setopt($this->oCurl, CURLOPT_HEADER, $returnheader);
        }
        
        curl_setopt($this->oCurl, CURLOPT_CUSTOMREQUEST, $this->getMethod());
                
        $data = $this->getData();
        if ($data) {
            curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        
        $header = $this->getHeader();
        
        $jsondata = $this->getJsonData();
        if ($jsondata) {
            curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, $jsondata);
            $header = array_merge($header, $this->getJsondataHeader());
        }
        
        if ($header) {
            curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, $header);
        }
        
        $cookie = $this->getCookie();
        if ($cookie) {
            curl_setopt($this->oCurl, CURLOPT_COOKIE, $cookie);
        }
        
        // 指定使用ipv4来解析dns，防止curl默认使用ipv6去解析dns造成请求超时
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($this->oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        
        curl_setopt($this->oCurl, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($this->oCurl, CURLOPT_CONNECTTIMEOUT, $this->getConnecttimeout());
        curl_setopt($this->oCurl, CURLOPT_RETURNTRANSFER, 1);        
        
        $sContent = curl_exec($this->oCurl);
        
        if ($errno = curl_errno($this->oCurl)) {
            $error_message = curl_strerror($errno);
            $msg = "cURL error ({$errno}): {$error_message}";            
            throw new Exception($msg);
        }
        
        curl_close($this->oCurl);
        
        return $sContent;
    }
    
    private function replaceHostByIp($url)
    {
        $arr = parse_url($url);
        if (! $arr) {
            $msg = '域名解析失败';            
            throw new Exception($msg);
        }
        
        $host = $arr['host'];
        $host = 'act.superpopgames.com';
        
        $header = $this->getHeader();
        $header[] = 'Host:'.$host;
        $this->setHeader($header);
        
        $ip = gethostbyname($host);
        $newurl = str_replace($host, $ip, $url);
        
        return $newurl;
    }

    private function getMethod()
    {
        if (empty($this->method)) {
            throw new Exception('method为空');
        }
        return $this->method;
    }

    private function getUrl()
    {
        if (empty($this->url)) {
            throw new Exception('url为空');
        }
        return $this->url;
    }

    private function getData()
    {
        return $this->data;
    }
    
    private function getJsonData()
    {
        return $this->jsondata;
    }

    private function getHeader()
    {
        return $this->header;
    }

    private function getCookie()
    {
        return $this->cookie;
    }

    private function getTimeout()
    {
        return $this->timeout;
    }

    private function getConnecttimeout()
    {
        return $this->connecttimeout;
    }

    private function getReturnheader()
    {
        return (int) $this->returnheader;
    }

    private function getJsondataHeader()
    {
        if ($this->getJsonData()) {
            return ['Content-Type:application/json', 'Content-Length:' . strlen($this->getJsonData())];    
        } else {
            return [];
        }
    }
    
    /*
     * 判断是否是URL
     */
    private function isUrl($url)
    {
        if (parse_url($url)) {
            return true;
        }
        return false;
    }
}

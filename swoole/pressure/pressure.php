<?php

/**
 * #php pressure.php -i "222.73.154.140" -p 80 -h "act.superpopgames.com" -u "/mcenter/index.php?c=index&f=index" -t 16 -c 10000 -pe 0
 * 参数说明
 * -i   ip
 * -p   port
 * -h   host
 * -u   uri
 * -m   method  默认为GET
 * -pa  params  当-m为POST时，参数以字符串形式传输，后台会用parse_str转为数组形式
 * -t   processNum  需要创建的进程数
 * -c   eachProcessExecTimes    每个进程执行的请求数
 * -pe  isperpetual     是否永久循环
 */

$argvparams = $argv;


array_shift($argvparams);

$argvkeys = [];
$argvvalues = [];
$argvcount = count($argvparams);
for ($i=0; $i<$argvcount; $i++) {
    if ($i%2 == 0) {
        if (strpos($argvparams[$i], '-') === false) {
            echo "参数名{$argvparams[$i]}错误";die;
        }
        $argvkeys[] = trim($argvparams[$i], '-');
    } else {
        $argvvalues[] = $argvparams[$i];
    }
}
$argvArr = array_combine($argvkeys, $argvvalues);


/*
$ip = '222.73.154.140';
$port = 80;
$host = 'act.superpopgames.com';
$uri = '/mcenter/index.php?c=index&f=index';
$method = 'GET';
$params = [];

$processNum = 1;
$eachProcessExecTimes = 1;
*/

$ip = isset($argvArr['i'])?$argvArr['i']:'';
$port = isset($argvArr['p'])?$argvArr['p']:'';
$port = (int)$port;
$host = isset($argvArr['h'])?$argvArr['h']:'';
$uri = isset($argvArr['u'])?$argvArr['u']:'';
$method = isset($argvArr['m'])?$argvArr['m']:'GET';
$params = isset($argvArr['pa'])?parse_str($argvArr['pa']):[];

if (empty($ip) || empty($port) || empty($uri)) {
    echo "-i -p -u参数为必填参数";die;
}

$processNum = isset($argvArr['t'])?$argvArr['t']:1;
$eachProcessExecTimes = isset($argvArr['c'])?$argvArr['c']:1;
$isperpetual = isset($argvArr['pe'])?$argvArr['pe']:true;
$processNum = (int)$processNum;
$eachProcessExecTimes = (int)$eachProcessExecTimes;
$isperpetual = (boolean)$isperpetual;

$client = new Client($ip, $port);
$client->setHost($host)->setUri($uri)->setMethod($method)->setParams($params);

$process = new Process($client);
$process->setProcessNum($processNum)->setEachProcessExecTimes($eachProcessExecTimes)->setIsPerpetual($isperpetual)->start();

class Process{
    private $mpid=0;
    private $works=[];        
    private $newIndex=0; 

    private $processNum = 1;
    private $eachProcessExecTimes = 1;
    private $isperpetual = false;
    
    private $client = null;    

    public function __construct(Client $client){
        $this->client = $client;
    }
    
    public function start()
    {
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
        $processNum = $this->getProcessNum();
        for ($i=0; $i < $processNum; $i++) {
            $this->CreateProcess($i);
        }
    }

    public function CreateProcess($index=null){
        $process = new swoole_process(function(swoole_process $worker)use($index){
            if(is_null($index)){
                $index=$this->newIndex;
                $this->newIndex++;
            }
            swoole_set_process_name(sprintf('php-pressure:%s',$index));
            $eachProcessExecTimes = $this->getEachProcessExecTimes();
            for ($j = 0; $j < $eachProcessExecTimes; $j++) {
                $this->checkMpid($worker);
                
                $this->client->httpRequest(function(swoole_http_client $cli){
                    $body = $cli->body;
                    //$cli->close();
                    $arr = json_decode($body, true);
                    if($arr['ret'] == 1) {
                        echo "OK\r\n";
                    } else {
                        echo "Error\r\n";
                    }
                });
            }
        }, false, 1);
        $pid=$process->start();
        $this->works[$index]=$pid;
        return $pid;
    }
    public function checkMpid(&$worker){
        if(!swoole_process::kill($this->mpid,0)){
            $worker->exit();
        }
    }

    public function rebootProcess($ret){
        $pid=$ret['pid'];
        $index=array_search($pid, $this->works);
        if($index!==false){
            $index=intval($index);
            $new_pid=$this->CreateProcess($index);;
            return;
        }
        throw new \Exception('rebootProcess Error: no pid');
    }

    public function processWait(){
        while(1) {
            if(count($this->works)){
                $ret = swoole_process::wait();
                if ($ret && $this->getIsPerpetual()) {
                    $this->rebootProcess($ret);
                }
            }else{
                break;
            }
        }
    }
    
    public function setProcessNum($processNum)
    {
        $this->processNum = $processNum;
        return $this;
    }
    
    public function setEachProcessExecTimes($eachProcessExecTimes)
    {
        $this->eachProcessExecTimes = $eachProcessExecTimes;
        return $this;
    }
    
    public function setIsPerpetual($isPerpetual)
    {
        $this->isPerpetual = $isPerpetual;
        return $this;
    }
    
    private function getProcessNum()
    {
        return $this->processNum;
    }
    
    private function getEachProcessExecTimes()
    {
        return $this->eachProcessExecTimes;
    }
    
    private function getIsPerpetual()
    {
        return $this->isPerpetual;
    }
}

class Client
{
    private $cli = null;
    
    private $ip = '';
    private $port = 0;
    private $host = '';
    private $header = [];
    private $uri = '';
    private $method = 'GET';
    private $params = [];
    private $timeout = 1;
    
    public function __construct(String $ip, int $port)
    {
        $this->setIp($ip);
        $this->setPort($port);
        return $this;
    }
    
    public function httpRequest(Closure $callback)
    {
        $this->cli = new swoole_http_client($this->getIp(), $this->getPort());
        
        $header = $this->getHeader();
        
        $host = $this->getHost();
        if (!empty($host)) {
            $hostHeader = ['Host'=>$this->getHost()];
            $header = array_merge($header, $hostHeader);
        }
        
        $this->cli->setHeaders($header);
        $this->cli->set([
            'timeout' => $this->getTimeout()
        ]);
        
        $uri = $this->getUri();
        $method = $this->getMethod();
        if ($method == 'GET') {
            $this->cli->get($uri, $callback);
        } elseif ($method == 'POST') {
            $this->cli->post($uri, $this->getParams(), $callback);
        }
    }
    
    public function setIp(String $ip)
    {
        $this->ip = $ip;
    }
    
    public function setPort(int $port)
    {
        $this->port = $port;        
    }
    
    public function setHost(String $host)
    {
        $this->host = $host;
        return $this;
    }
    
    public function setHeader(Array $header)
    {
        $this->header = $header;
        return $this;
    }
    
    public function setUri(String $uri)
    {
        $this->uri = $uri;
        return $this;
    }
    
    public function setMethod(String $method)
    {
        $this->method = $method;
        return $this;
    }
        
    public function setParams(Array $params)
    {
        $this->params = $params;
        return $this;
    }
    
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }
    
    private function getIp()
    {
        return $this->ip;
    }
    
    private function getPort()
    {
        return $this->port;
    }
    
    private function getHost()
    {
        return $this->host;
    }
    
    private function getHeader()
    {
        return $this->header;
    }
    
    private function getUri()
    {
        if (empty($this->uri)) {
            throw new Exception('uri不能为空');
        }
        return $this->uri;
    }
    
    private function getMethod()
    {
        if (!in_array($this->method, ['GET', 'POST'])) {
            throw new Exception('目前请求方式只支持GET或POST');
        }
        return $this->method;
    }
    
    private function getParams()
    {
        return $this->method;
    }
    
    private function getTimeout()
    {
        return $this->timeout;
    }
}















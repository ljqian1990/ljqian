<?php
namespace Pressure\Libraries;

use Exception;
use Pressure\Configs\Schema;

class Parse
{
    private $oSchema = null;
    
    // 公用的字段
    private $schema = null;
    private $ip = '';
    private $port = 0;
    private $callback = null;
        
    // 压测http需要的字段
    private $host = '';
    private $uri = '';
    private $method = '';
    private $params = [];
    
    // 压测redis需要用的的字段
    private $password = '';
    private $database = 0;
    private $key = '';
    private $value = '';
    
    // 配置tcp需要用的字段
    private $sendata = '';
    
    // 配置mysql需要用的字段
    private $user = '';
    private $charset = 'utf8';
    private $sql = '';
    
    // 配置process的字段
    private $processNum = 1;
    private $eachProcessExecTimes = 1;
    private $isperpetual = true;
    
    private $argvArr = [];
    
    public function __construct(Array $argv, Schema $oSchema)
    {
        $this->setOschema($oSchema);
        
        $argvparams = $argv;
        array_shift($argvparams);
        
        $argvkeys = [];
        $argvvalues = [];
        $argvcount = count($argvparams);
        for ($i=0; $i<$argvcount; $i++) {
            if ($i%2 == 0) {
                if (strpos($argvparams[$i], '-') === false) {
                    throw new Exception("参数名{$argvparams[$i]}错误");                    
                }
                $argvkeys[] = trim($argvparams[$i], '-');
            } else {
                $argvvalues[] = $argvparams[$i];
            }
        }
        $argvArr = array_combine($argvkeys, $argvvalues);
        $this->setArgvArr($argvArr);
        
        $schema = $this->getArgv('s');
        if (empty($schema)) {
            throw new Exception('-s参数为必须参数');            
        }        
        $this->setSchema($schema);
        
        $this->run();
    }
    
    private function run()
    {
        $this->setIp($this->getArgv('i'));
        $this->setPort(intval($this->getArgv('p', 0)));
        $this->setCallback($this->getArgv('cb', $this->getSchema()));
        
        if (! ($this->getIp() && $this->getPort())) {
            throw new Exception('-i -p 参数为必填参数');
        }
        
        $schemalist = $this->getOschema()->supportList();
        if (! in_array($this->getSchema(), $schemalist)) {
            throw new Exception('-s参数设置不正确');
        }
        
        call_user_func([$this, $this->getSchema() . 'Handler']);        
        
        $this->setProcessNum($this->getArgv('t', 1));
        $this->setEachProcessExecTimes($this->getArgv('c', 1));
        $this->setIsperpetual(boolval($this->getArgv('pe', true)));        
    }
    
    /**
     * 压测http
     * #php index.php -i "222.73.154.140" -p 80 -h "act.superpopgames.com" -u "/mcenter/index.php?c=index&f=index" -t 16 -c 10000 -pe 0 -s "http"
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
     * -s   设置压测的模式
     */
    private function httpHandler()
    {
        $this->setHost($this->getArgv('h'));
        $this->setUri($this->getArgv('u'));
        $this->setMethod($this->getArgv('m', 'GET'));
        $this->setParams($this->getArgv('pa', ''));
        
        if (! $this->getUri()) {
            throw new Exception('-u参数为必填参数');
        }
    }
    
    /**
     * 压测redis
     * #php index.php -i "172.30.104.94" -p 6379 -t 200 -c 20 -pe 0 -pwd "" -db "" -k "pressure:swoole:test:key" -v "ljqian" -s "redis"
     * 参数说明
     * -i   ip
     * -p   port
     * -t   processNum  需要创建的进程数
     * -c   eachProcessExecTimes    每个进程执行的请求数
     * -pe  isperpetual     是否永久循环
     * -pwd 设置redis密码
     * -db  设置redis数据库
     * -k   设置redis的key值
     * -v   设置redis的key对应的value值
     * -s   设置压测的模式
     */
    private function redisHandler()
    {
        $this->setPassword($this->getArgv('pwd'));
        $this->setDatabase($this->getArgv('db', ''));
        $this->setKey($this->getArgv('k'));
        $this->setValue($this->getArgv('v'));
        
        if (! ($this->getKey() && $this->getValue())) {
            throw new Exception('-k -v参数为必填参数');
        }
    }
    
    /**
     * 压测tcp
     * #php index.php -i "127.0.0.1" -p 9601 -sd "ljqian" -t 1 -c 1 -pe 0 -s "tcp"
     * 
     * 参数说明
     * -sd  sendata 设置需要通过tcp发送的内容
     */
    private function tcpHandler()
    {
        $this->setSendata($this->getArgv('sd'));        
    }
    
    /**
     * 压测websocket
     * #php index.php -i "127.0.0.1" -p 40100 -u "/" -sd "ljqian" -t 1 -c 1 -pe 0 -s "websocket"
     *
     * 参数说明
     * -sd  sendata 设置需要通过tcp发送的内容
     */
    private function websocketHandler()
    {
        $this->setUri($this->getArgv('u'));
        $this->setSendata($this->getArgv('sd'));
    }
    
    /**
     * 压测udp
     * #php index.php -i "192.168.12.192" -p 20000 -sd "192.168.12.132" -t 1 -c 1 -pe 0 -s "udp"
     *
     * 参数说明
     * -sd  sendata 设置需要通过tcp发送的内容
     */
    private function udpHandler()
    {
        $this->setSendata($this->getArgv('sd'));
    }
    
    /**
     * 压测mysql
     * #php index.php -i "127.0.0.1" -p 3306 -u "root" -pwd "ljqian1990" -db "ip2region" -sql "select * from ip_list_995;" -t 1 -c 1 -pe 0 -s "mysql"
     *
     * 参数说明
     * -u       user 用户名
     * -pwd     password    密码
     * -db      database    数据库
     * -charset charset     数据库编码格式
     * -sql     sql         需要执行的sql语句
     */
    private function mysqlHandler()
    {
        $this->setUser($this->getArgv('u'));
        $this->setPassword($this->getArgv('pwd'));
        $this->setDatabase($this->getArgv('db'));
        $this->setCharset($this->getArgv('charset', 'utf8'));
        $this->setSql($this->getArgv('sql'));
        
        if (! ($this->getUser() && $this->getDatabase() && $this->getSql())) {
            throw new Exception('-u -db -sql参数为必填参数');
        }
    }
    
    private function getArgv(String $param, $default = '')
    {
        return isset($this->getArgvArr()[$param]) ? $this->getArgvArr()[$param] : $default;
    }
    
    
    private function setSchema(String $schema)
    {
        $this->schema = $schema;
    }
    
    private function setIp(String $ip)
    {
        $this->ip = $ip;
    }
    
    private function setPort(int $port)
    {
        $this->port = $port;
    }
    
    private function setHost(String $host)
    {
        $this->host = $host;
    }
    
    private function setUri(String $uri)
    {
        $this->uri = $uri;
    }
    
    private function setMethod(String $method)
    {
        $this->method = $method;
    }
    
    private function setParams(String $params)
    {
        if (!empty($params)) {
            $this->params = parse_str($params);
        }
    }
    
    private function setPassword(String $password)
    {
        $this->password = $password;
    }
    
    private function setDatabase(String $database)
    {
        $this->database = $database;
    }
    
    private function setKey(String $key)
    {
        $this->key = $key;
    }
    
    private function setValue(String $value)
    {
        $this->value = $value;
    }
    
    private function setProcessNum(int $processNum)
    {
        $this->processNum = $processNum;
    }
    
    private function setEachProcessExecTimes(int $eachProcessExecTimes)
    {
        $this->eachProcessExecTimes = $eachProcessExecTimes;
    }
    
    private function setIsperpetual(bool $isperpetual)
    {
        $this->isperpetual = $isperpetual;
    }
    
    private function setArgvArr(Array $argvArr)
    {
        $this->argvArr = $argvArr;
    }
    
    private function setOschema(Schema $oSchema)
    {
        $this->oSchema = $oSchema;
    }
    
    private function setCallback(String $callback)
    {
        $this->callback = $callback;
    }
    
    private function setSendata(String $sendata)
    {
        $this->sendata = $sendata;
    }
    
    private function setUser(String $user)
    {
        $this->user = $user;
    }
    
    private function setCharset(String $charset)
    {
        $this->charset = $charset;
    }
    
    private function setSql(String $sql)
    {
        $this->sql = $sql;
    }
    
    public function getSchema()
    {
        return $this->schema;
    }
    
    public function getIp()
    {
        return $this->ip;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getDatabase()
    {
        return $this->database;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getProcessNum()
    {
        return $this->processNum;
    }
    
    public function getEachProcessExecTimes()
    {
        return $this->eachProcessExecTimes;
    }
    
    public function getIsperpetual()
    {
        return $this->isperpetual;
    }
    
    public function getCallback()
    {
        return $this->callback;
    }
    
    public function getSendata()
    {
        return $this->sendata;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getCharset()
    {
        return $this->charset;
    }
    
    public function getSql()
    {
        return $this->sql;
    }
    
    private function getArgvArr()
    {
        return $this->argvArr;
    }
    
    private function getOschema()
    {
        return $this->oSchema;
    }
}
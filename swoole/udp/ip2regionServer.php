 <?php
$serv = new swoole_server("0.0.0.0", 20000, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);   
$serv->set(['worker_num'=>16, 'max_request'=>10000, 'max_conn'=>20000, 'daemonize'=>1, 'log_file'=>'/tmp/swoole/ip2region/log/error.log']);

#$dbGlobal = null;
$serv->on('WorkerStart', function($serv, $worker_id) {
    /*
    $db = new swoole_mysql;    
    global $dbGlobal;
    $dbGlobal = $db;
    */
});

$serv->on('Packet', function ($serv, $ip, $clientInfo) {
    include_once(dirname(__FILE__).'/vendor/autoload.php');    
    $ip2region = new Ip2Region();    
    $info = $ip2region->btreeSearch($ip);    
    $info['ip'] = $ip;
    $serv->sendto($clientInfo['address'], $clientInfo['port'], json_encode($info));    
    
    $swoole_mysql = new Swoole\Coroutine\MySQL();
    $swoole_mysql->connect([
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => 'ljqian1990',
        'database' => 'ip2region',
        'charset' => 'utf8',
        'timeout' => 2
    ]);
    
    $tablename = 'ip_list_'.$info['city_id'];
    $createsql = <<<EOF
CREATE TABLE IF NOT EXISTS `{$tablename}` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `ipaddr` varchar(15) NOT NULL,
 `region` varchar(50) NOT NULL, 
 `num` int(11) NOT NULL,
 `datetime` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `ipaddr` (`ipaddr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
EOF;
    $swoole_mysql->query($createsql);
    $ret = $swoole_mysql->query('select count(*) as `count` from '.$tablename.' where `ipaddr`="'.$ip.'"');
    if ($ret[0]['count'] == 0) {
        $time = date('Y-m-d H:i:s');
        $sql = "insert into {$tablename}(`ipaddr`,`region`,`num`,`datetime`) values ('{$info['ip']}', '{$info['region']}', 1, '{$time}')";
    } else {
        $sql = "update {$tablename} set `num`=`num`+1 where `ipaddr`='{$info['ip']}'";
    }
    $swoole_mysql->query($sql);
});

$serv->start(); 
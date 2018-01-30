<?php
/**
 * 压测http
 * #php index.php -i "222.73.154.140" -p 80 -h "act.superpopgames.com" -u "/mcenter/index.php?c=index&f=index" -t 1 -c 1 -pe 0 -s "http"
 * 
 * 压测redis
 * #php index.php -i "w1.dev.ztgame.com" -p 6379 -t 1 -c 1 -pe 0 -pwd "" -db "" -k "pressure:swoole:test:key" -v "ljqian" -s "redis"
 * 
 * 压测tcp
 * #php index.php -i "127.0.0.1" -p 9601 -sd "ljqian" -t 1 -c 1 -pe 0 -s "tcp"
 * 
 * 压测websocket
 * #php index.php -i "127.0.0.1" -p 40100 -u "/" -sd "ljqian" -t 1 -c 1 -pe 0 -s "websocket"
 * 
 * 压测udp
 * #php index.php -i "192.168.12.192" -p 20000 -sd "192.168.12.132" -t 1 -c 1 -pe 0 -s "udp"
 * 
 * 压测mysql
 * #php index.php -i "127.0.0.1" -p 3306 -u "root" -pwd "ljqian1990" -db "ip2region" -sql "select * from ip_list_995;" -t 1 -c 1 -pe 0 -s "mysql"
 */
include_once dirname(__FILE__). '/vendor/autoload.php';

use Pressure\Libraries\Process;
use Pressure\Libraries\Parse;
use Pressure\Configs\Schema;

$oSchema = new Schema();
$oParse = new Parse($argv, $oSchema);

$callback = (new ReflectionClass('Pressure\\Callback\\'. ucfirst($oParse->getCallback()) . 'CB'))->newInstance();

$client = (new ReflectionClass('Pressure\\Clients\\'. ucfirst($oParse->getSchema()) . 'client'))->newInstanceArgs([$oParse, $callback]);

$process = new Process($client, $oParse);
$process->start();
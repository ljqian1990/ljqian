<?php
/**
 * @version  1.0
 * @author   Ivan
 * @date     
 */

// for ($i=0;$i<5000;$i++){
//     $cli = new swoole_http_client('127.0.0.1', 9501);
//     $cli->setHeaders(['Trace-Id' => md5(time()),]);
//     $cli->on('message', function ($_cli, $frame) {
//         //var_dump($frame);
//     });
//     $cli->upgrade('/', function ($cli) {
//         //echo $cli->body;
//         $cli->push("hello world");
//     });
// }

// echo 'done';

(new class{
    public $mpid=0;
    public $works=[];
    public $max_precess=4;
    public $new_index=0;

    public function __construct(){
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
            $this->CreateProcess();
        }
    }

    public function CreateProcess($index=null){
        $process = new swoole_process(function(swoole_process $worker)use($index){
            if(is_null($index)){
                $index=$this->new_index++;
            }
            swoole_set_process_name(sprintf('php-ps:%s',$index));
            for ($i=0;$i<4000;$i++){
                //$cli = new swoole_http_client('192.168.39.4', 41010);
                $cli = new swoole_http_client('101.226.182.36', 41010);
                $cli->set(['timeout' => -1]);
                $cli->setHeaders(['Trace-Id' => md5(time()),]);
                $cli->on('message', function ($_cli, $frame) {
                    //var_dump($frame);
                });

                $cli->on('error', function ($cli) {
                  echo "on error errCode is :{$cli->errCode}\n";
                  echo "on error statusCode is :{$cli->statusCode}\n";
                });

                $cli->on('close', function ($cli) {
                    echo "on close errCode is :{$cli->errCode}\n";
                    echo "on close statusCode is :{$cli->statusCode}\n";
                });

                $cli->upgrade('/', function ($cli) {
                    if ($cli->errCode !== 0){
                        echo "on upgrade errCode is :{$cli->errCode}\n";
                        echo "on upgrade statusCode is :{$cli->statusCode}\n";
                    } else {
                        $cli->push("broadcast;hello world");
                    }
                });
            }
        }, false, false);

        $pid=$process->start();
        $this->works[$index]=$pid;
        return $pid;
    }
    public function checkMpid(&$worker){
        if(!swoole_process::kill($this->mpid,0)){
            $worker->exit();
            // 这句提示,实际是看不到的.需要写到日志中
            //file_put_contents(filename, data,) "Master process exited, I [{$worker['pid']}] also quit\n";
        }
    }

    public function rebootProcess($ret){
        $pid=$ret['pid'];
        $index=array_search($pid, $this->works);
        if($index!==false){
            $index=intval($index);
            $new_pid=$this->CreateProcess($index);
            echo "rebootProcess: {$index}={$new_pid} Done\n";
            return;
        }
        throw new \Exception('rebootProcess Error: no pid');
    }

    public function processWait(){
        while(1) {
            if(count($this->works)){
                $ret = swoole_process::wait();
                if ($ret) {
                    $this->rebootProcess($ret);
                }
            }else{
                break;
            }

        }
    }
});

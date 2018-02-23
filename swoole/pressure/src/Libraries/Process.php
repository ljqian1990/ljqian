<?php
namespace Pressure\Libraries;

use Pressure\Clients\Client;
use Pressure\Callback\Base as CallbackBase;
use swoole_process;

class Process{
    private $mpid=0;
    private $works=[];        
    private $newIndex=0; 

    private $processNum = 1;
    private $eachProcessExecTimes = 1;
    private $isperpetual = false;
    
    private $client = null;

    public function __construct(Client $client, Parse $oParse){
        $this->client = $client;        
        
        $this->setProcessNum($oParse->getProcessNum());
        $this->setIsPerpetual($oParse->getIsperpetual());
        $this->setEachProcessExecTimes($oParse->getEachProcessExecTimes());
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
                
                $this->client->send();
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
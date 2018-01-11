<?php
class Server
{
	private $serv = null;
	
	private function init()
	{
		// create a server instance
		$this->serv = new swoole_server("0.0.0.0", 40005); 		
	}
	
	private function connect()
	{
		// attach handler for connect event, once client connected to server the registered handler will be executed
		$this->serv->on('connect', function ($serv, $fd){  
		//	echo "Client:Connect.\n";
		});		
	}
	
	private function receive()
	{
		// attach handler for receive event, every piece of data received by server, the registered handler will be
		// executed. And all custom protocol implementation should be located there.
		$this->serv->on('receive', function ($serv, $fd, $from_id, $data) {
//var_dump($data);exit;
			$client = new Client();
			$str = $client->run($data, $serv, $fd);//var_dump($str);exit;
			//$str = 'ljqian22';
//			$serv->send($fd, $str."\0");
		});
	}
	
	private function close()
	{
		$this->serv->on('close', function ($serv, $fd) {
	//		echo "Client: Close.\n";
		});		
	}
	
	private function start()
	{
		// start our server, listen on port and ready to accept connections
		$this->serv->start();
	}
	
	public function run()
	{
		$this->init();
		$this->connect();
		$this->receive();
		$this->close();
		$this->start();
	}
}

$server = new Server();
$server->run();

class Client
{
	public function run($account, $serv, $fd)
	{
		$account = 'ljqian22';
		$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
		$client->on("connect", function(swoole_client $cli) use ($account) {
		    $cli->send("GET /getinfo?account={$account}&all=");
		});
		$client->on("receive", function(swoole_client $cli, $data) use ($serv, $fd){
//		    echo "Receive: $data";
			$data .= 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg';
		    $serv->send($fd, $data . "\0");
		});
		$client->on("error", function(swoole_client $cli){
//		    echo "error\n";
		});
		$client->on("close", function(swoole_client $cli){
//		    echo "Connection close\n";
		});
		$client->connect('192.168.12.132', 50001);	
	}
}







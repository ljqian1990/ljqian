<?php
class Server
{
	private $serv = null;
	
	private function init()
	{
		// create a server instance
		$this->serv = new swoole_server("0.0.0.0", 40003); 		
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
			$str = $client->run($data);//var_dump($str);exit;
			//$str = 'ljqian22';
			$serv->send($fd, $str."\0");
			$serv->close($fd);
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
	private $client = null;
	
	private function init()
	{
		$this->client = new swoole_client(SWOOLE_SOCK_TCP);
	}
	
	private function connect()
	{
		//连接到服务器
		if (!$this->client->connect('192.168.12.132', 50001, 300))
		{
			die("connect failed.");
		}
	}
	
	private function send($account)
	{
		if (!$this->client->send("GET /getinfo?account={$account}&all="))
		{
			die("send failed.");
		}
	}
	
	private function recv()
	{
		//从服务器接收数据
		$data = $this->client->recv();
		if (!$data)
		{
			die("recv failed.");
		}
		return $data;
	}
	
	private function close()
	{
		//关闭连接
		$this->client->close();
	}
	
	public function run($account)
	{
		$this->init();
		$this->connect();
		$this->send($account);
		$data = $this->recv();
		$this->close();
		return $data;
	}
}







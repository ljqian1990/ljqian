<?php
class Server
{
	private $serv = null;
	
	private function init()
	{
		// create a server instance
		$this->serv = new swoole_server("0.0.0.0", 20001); 		
	}
	
	private function connect()
	{
		// attach handler for connect event, once client connected to server the registered handler will be executed
		$this->serv->on('connect', function ($serv, $fd){  
			echo "Client:Connect.\n";
		});		
	}
	
	private function receive()
	{
		// attach handler for receive event, every piece of data received by server, the registered handler will be
		// executed. And all custom protocol implementation should be located there.
		$this->serv->on('receive', function ($serv, $fd, $from_id, $ticket) {

			$ticket = str_replace(array("\r\n", "\n", "\r", " "), '', $ticket);

			var_dump($ticket);

			$userhex = substr($ticket, 0, -10);

			echo 'userhex:'.$userhex."\n\r";   

			$check = pack('H*', $userhex);

			echo 'check:'.$check."\n\r";

			$check_1 = $check.',0f681f0f7e9204b5b18e7afca6863af6';
		#    $check_1 = '701024067,ljqian23,ljqian23,0f681f0f7e9204b5b18e7afca6863af6';

			echo 'check_1:'.$check_1."\n\r";

			$me_value = substr(md5($check_1), 0, 10);

			echo 'me_value:'.$me_value."\n\r";

			$md5_v = substr($ticket, -10, 10);

			echo 'md5_v:'.$md5_v."\n\r";

			if ($me_value == $md5_v && !empty($userhex)) {

				echo 'ok'."\n\r";

				$user_str = pack('H*', $userhex);
				list($user['uid'], $user['account'], $user['showaccount']) = explode(',', $user_str);
				$user_str = json_encode($user);
			} else {

				echo 'false'."\n\r";

				$user_str = 0;
			}
			echo $user_str;
			$serv->send($fd, $user_str."\r\n");
		});
	}
	
	private function close()
	{
		$this->serv->on('close', function ($serv, $fd) {
			echo "Client: Close.\n";
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









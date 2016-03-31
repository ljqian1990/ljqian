<?php
/**
 * ��������ģʽ
 *
 * ����һ�����ڴ�������Ľӿ�,�������������һ����ʵ����,ʹ��һ�����ʵ�����ӳٵ�������
 */
class DBFactory {
	public static function create($type) {
		$class = $type . "DB";
		return new $class ();
	}
}
interface DB {
	public function connect();
	public function exec();
}
class MysqlDB implements DB {
	public function __construct() {
		echo "mysql db<br/>";
	}
	public function connect() {
	}
	public function exec() {
	}
}
class PostgreDB implements DB {
	public function __construct() {
		echo "Postgre db<br/>";
	}
	public function connect() {
	}
	public function exec() {
	}
}
class MssqlDB implements DB {
	public function __construct() {
		echo "mssql db<br/>";
	}
	public function connect() {
	}
	public function exec() {
	}
}

$oMysql = DBFactory::create ( "Mysql" );
$oPostgre = DBFactory::create ( "Postgre" );
$oMssql = DBFactory::create ( "Mssql" );
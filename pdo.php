<?php
class Database {
	private static $link = null;
	private static function getLink() {
		if (self::$link) {
			return self::$link;
		}
		
// 		$ini = _BASE_DIR . "config.ini";
// 		$parse = parse_ini_file ( $ini, true );
		
		$driver = 'mysql';
		$dsn = "${driver}:";
		$user = 'root';
		$password = '';
		$options = 'PDO::MYSQL_ATTR_INIT_COMMAND=set names utf8';
		$attributes = 'ATTR_ERRMODE=ERRMODE_EXCEPTION';
		
		$dsn_ini = array('host'=>'localhost', 'port'=>3306, 'dbname'=>'test');
		
		foreach ( $dsn_ini as $k => $v ) {
			$dsn .= "${k}=${v};";
		}
		
		self::$link = new PDO ( $dsn, $user, $password );
		
// 		foreach ( $attributes as $k => $v ) {
// 			self::$link->setAttribute ( constant ( "PDO::{$k}" ), constant ( "PDO::{$v}" ) );
// 		}
		
		return self::$link;
	}
	
	public static function __callStatic($name, $args) {
		$callback = array (
				self::getLink (),
				$name 
		);
		return call_user_func_array ( $callback, $args );
	}
}
?>

<?php
// examples

$stmt = Database::prepare ( "update ljqian set sex=:sex where id=:id;" );
$sex = 'sex+1';
$id = 1;
$stmt->bindValue(":sex", $sex, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute ();


var_dump ( $stmt->fetchAll () );
$stmt->closeCursor ();

?>
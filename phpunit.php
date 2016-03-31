<?php
abstract class Generic_Tests_DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
    // ֻʵ���� pdo һ�Σ������Ե�������װ�ػ���ʹ��
    static private $pdo = null;

    // ����ÿ�����ԣ�ֻʵ���� PHPUnit_Extensions_Database_DB_IDatabaseConnection һ��
    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }
}
?>
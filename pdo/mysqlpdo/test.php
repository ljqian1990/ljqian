<?php
use Zend\Db\Sql\Ddl\Column\Varbinary;
include 'mysql.class.php';

class Place
{

    private $db;

    private $configs = [
        'master' => [
            'host' => 'localhost',
            'port' => 3306,
            'user' => 'root',
            'password' => '',
            'database' => 'ljqian',
            'charset' => 'utf8',
            'pconnect' => false
        ],
        'slave' => [
            [
                'host' => 'localhost',
                'port' => 3306,
                'user' => 'root',
                'password' => '',
                'database' => 'ljqian',
                'charset' => 'utf8',
                'pconnect' => false
            ],
            [
                'host' => 'localhost',
                'port' => 3306,
                'user' => 'root',
                'password' => '',
                'database' => 'ljqian',
                'charset' => 'utf8',
                'pconnect' => false
            ]
        ]
    ];

    public function __construct()
    {
        $this->db = new mysqlDB($this->configs);
    }

    public function getAll($id)
    {
        $result = $this->db->selectOne('test', '*', array(
            'where' => array(
                'id' => $id
            )
        ));
        $result = array(
            $result
        );
        $result = $this->getList($id, $result);
        return $result;
    }

    public function getList($pid = 0, &$result = array())
    {
        $res = $this->db->select('test', '*', array(
            'where' => array(
                'name' => $pid
            )
        ));
        if (! empty($res)) {
            foreach ($res as $value) {
                $result[] = $value;
                $this->getList($value['id'], $result);
            }
        }
        return $result;
    }
}

$place = new Place();
$starttime = microtime(true);
foreach (range(0, 1000) as $value) {
    $result = $place->getAll(2);
}
$endtime = microtime(true);
echo $endtime - $starttime;
//4.1420001983643
//http://ljqian.local.com/pdo/mysqlpdo/test.php
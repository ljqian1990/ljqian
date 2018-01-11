<?php
$db = new swoole_mysql;
$server = array(
    'host' => 'localhost',
//    'port' => 3306,
    'user' => 'test',
    'password' => 'test',
    'database' => 'siteapi',
    'charset' => 'utf8', //ָ���ַ���
//    'timeout' => 2,  // ��ѡ�����ӳ�ʱʱ�䣨�ǲ�ѯ��ʱʱ�䣩��Ĭ��ΪSW_MYSQL_CONNECT_TIMEOUT��1.0��
);

$db->connect($server, function ($db, $r) {
    if ($r === false) {
        var_dump($db->connect_errno, $db->connect_error);
        die;
    }
    $sql = 'show tables';
    $db->query($sql, function(swoole_mysql $db, $r) {
        if ($r === false)
        {
            var_dump($db->error, $db->errno);
        }
        elseif ($r === true )
        {
            var_dump($db->affected_rows, $db->insert_id);
        }
        var_dump($r);
        $db->close();
    });
});

<?php
class ConnectionMySQL{
    //����
    private $host="localhost";
    //���ݿ��username
    private $name="root";
    //���ݿ��password
    private $pass="";
    //���ݿ�����
    private $table="test";
    //������ʽ
    private $ut="utf8";

    //���캯��
    function __construct($ut=''){
    	if(!empty($ut)){
    		$this->ut = $ut;
    	}        
        $this->connect();

    }

    //���ݿ������
    function connect(){
        $link = mysql_connect($this->host,$this->name,$this->pass) or die ($this->error());        
        mysql_select_db($this->table,$link) or die("û�����ݿ⣺".$this->table);
        mysql_query("SET NAMES '$this->ut'");
    }

    function query($sql, $type = '') {
        if(!($query = mysql_query($sql))) $this->show('Say:', $sql);
        return $query;
    }

    function show($message = '', $sql = '') {
        if(!$sql) echo $message;
        else echo $message.'<br>'.$sql;
    }

    function affected_rows() {
        return mysql_affected_rows();
    }

    function result($query, $row) {
        return mysql_result($query, $row);
    }

    function num_rows($query) {
        return @mysql_num_rows($query);
    }

    function num_fields($query) {
        return mysql_num_fields($query);
    }

    function free_result($query) {
        return mysql_free_result($query);
    }

    function insert_id() {
        return mysql_insert_id();
    }

    function fetch_row($query) {
        return mysql_fetch_row($query);
    }

    function version() {
        return mysql_get_server_info();
    }

    function close() {
        return mysql_close();
    }

    //��$table���в���ֵ
    function fn_insert($table,$name,$value){
        $this->query("insert into $table ($name) value ($value)");
    }
    //����$idֵɾ����$table�е�һ����¼
    function fn_delete($table,$id,$value){
        $this->query("delete from $table where $id=$value");
        echo "idΪ". $id." �ļ�¼���ɹ�ɾ��!";
    }
}

$db =  new ConnectionMySQL();

$db->fn_insert('test','id,name,sex',"'','hongtenzone','M'");
// $db->fn_delete('test', 'id', 2);

?>
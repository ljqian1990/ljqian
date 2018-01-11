<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');
include dirname(dirname(__FILE__)) . '/pdo/mysqlpdo/mysql.class.php';

class CurlClass
{
    private $db;
    
    private $table = 'why';
    
    public function __construct()
    {
        $this->db = new mysqlDB();
    }
    
    public function get($url)
    {
        $ret = file_get_contents($url);
        preg_match_all('/<a href="(.*?)" class="post-(.*?)" rel="bookmark">(.*?)<\/a>/', $ret, $match);
        $ids = $match[2];
        foreach ($ids as $id) {
            $data = file_get_contents('https://www.10why.net/post/'.$id.'.html');
            preg_match('/<h1 class="entry-title">(.*?)<\/h1>/', $data, $matchtitle);
            $title = $matchtitle[1];
            preg_match('/<div class="entry-content">(.*?)<\/div>/', $data, $matchcontent);
            $content = strip_tags($matchcontent[1]);
            
            $this->save(['title'=>$title, 'content'=>$content]);
        }
    }
    

    
    private function save($data)
    {
        $this->db->insert($this->table, $data);
    }
}

$cc = new CurlClass();
$cc->get('https://www.10why.net/post/');
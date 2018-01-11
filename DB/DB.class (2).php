<?php
/**
* @description:数据库操作模型
* @author:任爽
* @date:2012-01-04
*/

class DB
{	
	//最后一次执行的sql
	private $lastSql = '';
	
	//配置列表
	private $_DBConfigs = null;
    
	//当前db的连接
	private $_dbLink = null;
	
	//最后一次查询操作的query_id
	private $_queryID = null;
	
	//是否强制使用主库
	private $_forceMaster = false;
	
	//连接池
	private $_dbConnectPool = Array();
	
	//为兼容原mysql 函数，临时保存受影响的记录条数
    private $_affectedRows = null;
    
	/**
	*
	* @param Array $db_configs :数据库配置数组
	*
	*/
	public function __construct($db_configs)
	{
		$this->setConfigs($db_configs);
	}
	
	/**
	*
	* @param Array $db_configs :数据库配置数组
	*
	* 完整的数据库配置示例
	*Array(
	* 	//唯一主库
	*	'master' => array('host'=>'localhost','port'=>'3306','user'=>'root','passwd'=>'123456','database'=>'test','charset'=>'utf8','pconnect'=>false),
	*	//多个从库
	*	'slave'  => array(
	*		array('host'=>'localhost','port'=>'3306','user'=>'root','passwd'=>'123456','database'=>'test','charset'=>'utf8','pconnect'=>false),
	*		array('host'=>'localhost','port'=>'3306','user'=>'root','passwd'=>'123456','database'=>'test','charset'=>'utf8','pconnect'=>false),
	*	)
	*)
	*/
	public function setConfigs($db_configs)
	{
		$this->_DBConfigs = $db_configs;
	}
	
	/**
	 * 组合各种条件查询并返回结果集
	 *
	 * 说明:$where 可以是字符串或数组,如果定义为数组则格式有如下两种:
	 *      $where = array('id'=>1,
	 *                     'name'=>'myphp');
	 *      解析后条件为: "id=1 AND name='myphp'"
	 * 
	 *      $where = array('id'=>array('>='=>1),
	 *                     'name'=>array('like'=>'%myphp%'));
	 *      解析后条件为: "id>=1 AND name LIKE '%myphp%'"
	 *      
	 *      $where = ['id' => ['in' => [1,2,3]], 'name' => ['in' => ['ljqian1', 'ljqian2']]];
	 *      解析后条件为：  "`id` in (1,2,3) and `name` in ('ljqian1','ljqian2')"
	 * 
	 * 注意:#where 中的条件解析后都是用 AND 连接条件,其它形式请直接用字符串的方法传值
	 * 
	 * @param string $fields :字段名
	 * @param string $tables :表
	 * @param mixed $where   :条件
	 * @param string $order  :排序字段
	 * @param string $limit  :返回记录行,格式 "0,10"
	 * @param string $group  :分组字段
	 * @param string $having :筛选条件
	 * @return array
	 */
	public function select($tables , $fields = '*' , $options=Array())
	{
		$sql = 'SELECT '.$fields.' FROM '.$tables;
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['having'])) $sql .= ' HAVING '.$options['having'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		if (isset($options['limit'])) $sql .= ' LIMIT '.$options['limit'];
		return $this->fetchAll($sql,array());		
	}


	/**
	 * 查询单条记录,组合各种条件查询并返回结果集
	 *
	 * @param string $fields :字段名
	 * @param string $tables :表
	 * @param mixed $where   :条件,详细请看 Select()成员
	 * @param string $order  :排序字段
	 * @param string $group  :分组字段
	 * @param string $having :筛选条件
	 * @return array
	 */
	public function selectOne($tables , $fields = '*' , $options=Array())
	{
		$sql = 'SELECT '.$fields.' FROM '.$tables;
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['having'])) $sql .= ' HAVING '.$options['having'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		$sql .= ' LIMIT 1';
		return $this->fetchRow($sql);
	}

	/**
     * 更新记录,执行 UPDATE 操作
     *
     * 说明: $arrSets 格式如下:
     *      $arrSets = array('uid'=>1,
     *                       'name'=>'myphp');
     * 
     * 解析后SET为: "uid=1,name='myphp'"
     * 
     * @param string $table  :表
     * @param array $arrSets :设置的字段值
     * @param mixed $where   :条件,详细请看 Select()成员
     * @param string $order  :排序字段
     * @param int $limit     :记录行
	 * @param string $group  :分组字段
     * @return int 			 :受影响的行数
     */
	public function update($table,$arrSets,$options=Array())
	{
		$sqlSet = $this->_parseUpdateSet($arrSets);
		$sql = sprintf("UPDATE %s SET %s",$table,$sqlSet);
		
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		if (isset($options['limit'])) $sql .= ' LIMIT '.$options['limit'];
		
		$result = $this->execute($sql);
        
		return $result;
	}

	/**
     * 插入记录,执行 INSERT 操作
     *
     * 说明:有关 $arrSets 数组的定义请看: Update()成员
     * 
     * @param string $table  :表名
     * @param array $arrSets :插入的字段
     * @return int           :新记录的ID
     */
	public function insert($table,$arrSets)
	{
		$ret = $this->_parseInsertSet($arrSets);
		
		$sql = sprintf("INSERT INTO %s(%s) VALUES(%s)",$table,$ret['key'],$ret['val']);
		$result = $this->execute($sql);
		if($result){
			$result = $this->lastInsertID();
		}
		return $result;
	}
	
	/**
     * replace 插入记录,执行 replace into 操作
     *
     * 说明:有关 $arrSets 数组的定义请看: Update()成员
     * 
     * @param string $table  :表名
     * @param array $arrSets :插入的字段
     * @return int           :新记录的ID
     */
	public function replace($table,$arrSets)
	{
		$ret = $this->_parseInsertSet($arrSets);
		
		$sql = sprintf("REPLACE INTO %s(%s) VALUES(%s)",$table,$ret['key'],$ret['val']);
		$result = $this->execute($sql);
		if($result){
			$result = $this->lastInsertID();
		}
		return $result;
	}
	
	/**
	 * 删除记录,执行 DELETE 操作,返回删除的记录行数
	 *
	 * @param string $table :表
	 * @param mixed $where  :条件,详细请看 select()成员
	 * @param string $order :排序字段
	 * @param string $limit :记录行
	 * @param string $group :分组
	 * @return int 			:受影响的行数
	 */
	public function delete($table,$options=Array())
	{
		$sql = "DELETE FROM $table";
		
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		if (isset($options['limit'])) $sql .= ' LIMIT '.$options['limit'];
		
		$result = $this->execute($sql);
        
		return $result;
	}

	/**
	 * 求记录数
	 *
	 * 说明:如果是求表的所有记录(没有WHERE),对于MyISAM表 $countField 请用 '*',否则请指定字段名
	 * 
	 * @param string $table      :表
	 * @param mixed $where       :条件      
	 * @param string $countField :COUNT字段名
	 * @param string $group      :分组
	 * @return int
	 */
	public function count($table,$countField='*',$options=Array())
	{
		$sql = sprintf("SELECT COUNT(%s) FROM %s",$countField,$table);
		
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		
		$row = $this->fetchRow($sql);
        
	    if ($row) return current($row);
		return 0;
	}
	
	/**
	 * 执行任何SQL语句
	 *
	 * 说明:$sql语句中可以传参数,格式如:"select * from user where userid=:uid and username=:name" 其中: ":uid"和":name" 表示参数变量
	 *     则必需定义$bind为: $bind=array('uid'=>3,
	 *                                  'name'=>'myphp')
	 *     表示$sql中 :uid 的值为3, :name 的值为'myphp'
	 * 
	 * 注意:SQL中的参数只能用于 WHERE 条件中
	 * 
	 * @param string $sql
	 * @param array $bind
	 * @return bool
	 */
	public function execute($sql, $bind=array())
	{	
		$sql = $this->_parseSQL($sql,$bind);
		
		//执行更新操作后，强制使用主库，以避免主从延时带来的数据不一致问题
		$this->_forceMaster = true;
		$this->_setCurrentConnect(true);
		
		$this->lastSql = $sql;
		
// 		GLog::info(['sql'=>$sql]);
		//返回受影响的行数
		$this->_affectedRows = $this->_dbLink->exec($sql);
		
		return $this->_affectedRows;
	}

	/**
	 * 返回最后执行 Insert() 操作时表中有 auto_increment 类型主键的值
	 * 
	 * @return int
	 */
	public function lastInsertID()
	{
		/* [PHP手册]
           如果 AUTO_INCREMENT 的列的类型是 BIGINT，则 mysql_insert_id() 返回的值将不正确。
           可以在 SQL 查询中用 MySQL 内部的 SQL 函数 LAST_INSERT_ID() 来替代。 
		*/
		//$this->_setCurrentConnect(true);
		//return mysql_insert_id($this->_dbLink);
		
		//主库
		$this->_setCurrentConnect(true);
		
		return $this->_dbLink->lastInsertId();
	}

	/**
	 * 最后 DELETE UPDATE 语句所影响的行数，PDO Mysql 的execute已经可以直接返回受影响行数，但为了兼容以前Mysql的老函数，故留此
	 *
	 * @return int
	 */
	public function affectedRows()
	{
        return $this->_affectedRows;
	}

	/**
	 * 返回处理后的查询二维结果集,返回的结果格式为:
	 * 
	 * 如果SQL的结果集为:
	 *   -uid- -name- -age-  (字段名)
	 *    u1    yuan   20    (第一行记录)
	 *    u2    zhan   19    (第二行记录)
	 * 
	 * 则则函数返回的数组值为:   
	 *   array('u1'=>array('uid'=>'u1','name'=>'yuan','age'=>20),
	 *         'u2'=>array('uid'=>'u2','name'=>'zhan','age'=>19)
	 *        )
	 * 
	 * 说明:有关 $sql和$bind 的用法请看 self::Execute()
	 * 
	 * @param string $sql
	 * @param array $bind
	 * @return array
	 */
	public function fetchAssoc($sql,$bind=array())
	{
		$result = array();
		$feachall =  $this->fetchAll($sql,$bind);
		foreach($feachall as $row){
			$result[current($row)] = $row;
		}
		
		return $result;
	}
	
	/**
	 * 执行SQL并返回所有结果集
	 *
	 * 说明:有关 $sql和$bind 的用法请看 self::execute()
	 * 
	 * @param string $sql
	 * @param array $bind
	 * @return array
	 */
	public function fetchAll($sql,$bind=array())
	{
        //从库
		$this->_setCurrentConnect(false);
        
		$result = Array();
		
		$bind = $this->bindToPDO($bind);
		
		$sth = $this->_dbLink->prepare($sql, Array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute($bind);        
		
		$this->lastSql = $sql;
		Glog::info(['sql'=>$sql]);
		
		return $sth->fetchAll();
	}

	/**
	 * 执行SQL并返回结果集的第一行(一维数组)
	 *
	 * 说明:有关 $sql和$bind 的用法请看 self::execute()
	 * 
	 * @param string $sql
	 * @param array $bind
	 * @return array
	 */
	public function fetchRow($sql,$bind=array())
	{
		$result = $this->fetchAll($sql,$bind);
		
		return empty($result)?array():$result[0];
	}
	
	/**
	 * 开始事务
	 * @return bool
	 */
	public function beginTransaction()
	{
		//主库
		$this->_setCurrentConnect(true);
		
		return $this->_dbLink->beginTransaction();
	}
	
	/**
	 * 提交事务
	 * @return bool
	 */
	public function commit()
	{
		//主库
		$this->_setCurrentConnect(true);
		
	    return $this->_dbLink->commit();
	}
	
	/**
	 * 事务回滚
	 * @return bool
	 */
	public function rollBack()
	{
		//主库
		$this->_setCurrentConnect(true);
		
	    return $this->_dbLink->rollBack();
	}
	
	/**
	 * 格式化用于数据库的安全字符串
	 *
	 * @param string $value
	 * @return string
	 */
	public function escapeValue($value)
	{
        if(empty($this->_dbLink)){
            //从库
		    $this->_setCurrentConnect(false);
        }
		
		$value = $this->_dbLink->quote($value);
        
	    return $value;
	}
	
	/**
	 * 取得最后一条执行的sql语句
	 */
    public function getLastSQL()
    {
    	return $this->lastSql;
    }
    
    /**
	 * 关闭所有已打开的数据库连接
	 */
	public function close()
	{
		foreach($this->_dbConnectPool as $key=>$val){
			unset($this->_dbConnectPool[$key]);
		}
	    
	    return true;
	}
	/* =================================================================================================
	以下为私有成员函数定义
	================================================================================================= */
	
	/**
	 * 获取一个数据库连接
	 *
	 * @param array $dbSet :某一数据库配置
	 * @return db_link
	 */
	private function _getDBConnect($dbSet)
	{
        $dbSet['port'] = empty($dbSet['port'])? '3306' : $dbSet['port'];
        $dsn = "mysql:host={$dbSet['host']}; dbname={$dbSet['database']}; port={$dbSet['port']}; charset=utf8";
        
        $pdoParam = Array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
            PDO::ATTR_PERSISTENT => $dbSet['pconnect']?true:false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );
        
        $dbLink = new PDO($dsn, $dbSet['user'], $dbSet['passwd'], $pdoParam);
        
		return $dbLink;
	}
	
	/**
	 * 设置当前的 db handle
	 *
	 * @param boolean $isMaster :是否使用主库
	 * @return db_link
	 */
    private function _setCurrentConnect($isMaster = false)
    {	
    	//如果之前执行过写操作(_forceMaster)，则强制使用主库
    	if($this->_forceMaster)$isMaster =  true;
    	
        if($isMaster){
        	$_config = $this->_DBConfigs['master'];
        }else{
            $_config = $this->_DBConfigs['slave'][array_rand($this->_DBConfigs['slave'])];
        }
		
        $key = md5($_config['host'].$_config['port'].$_config['user'].$_config['database']);
        
    	if (!isset($this->_dbConnectPool[$key]))
		{
			$this->_dbConnectPool[$key] = $this->_getDBConnect($_config);
		}
		
		return $this->_dbLink = $this->_dbConnectPool[$key];
    }
	
	/**
	 * 解析字段名,防止字段名是关键字
	 */
	private function _parseField($fieldName)
	{
		return '`'.$fieldName.'`';
	}
	
	/**
	 * 判断预编译语句的key前是否有冒号，如果没有则补上，以兼容老Mysql和PDO
	 *
	 * @param array 预编译参数数组
	 * @return array
	 */
	private function bindToPDO($bind)
    {
        foreach($bind as $key=>$val)
        {
            if(substr($key,0,1) != ':')
            {
                $bind[':'.$key] = $val;
                unset($bind[$key]);
            }
        }
        
        return $bind;
    }
	
	/**
	 * 解析SQL语句中的值定义
	 *
	 * @param string $sql
	 * @param array $bind
	 * @return string
	 */
	private function _parseSQL( $sql, $bind=array())
	{   
		$searchArr = array();
		$replaceArr = array();
		if (count($bind))
		{
			foreach ($bind as $k=>$v)
			{
				$searchArr[] = ":$k";
				$replaceArr[] = $this->_parseValue($v);
			}
			$sql = str_replace($searchArr,$replaceArr,$sql);
		}
		return $sql;
	}

	/**
     * 根据值的类型返回SQL语句式的值
     *
     * @param unknown_type $val
     * @return unknown
     */
	private function _parseValue($val)
	{
		if (is_int($val) || is_float($val)) return $val;
		else return $this->escapeValue($val);
	}

	/**
	 * 解析 SQL WHERE 条件
	 *
	 * @param mixed $where
	 * @return string
	 */
	private function _parseWhere($where)
	{
		$sqlWhere = '1 ';
		if (is_array($where))
		{
			foreach ($where as $k=>$v)
			{			    
				$sqlWhere .= " AND ".$this->_parseField($k);
				if (is_array($v))
				{
					foreach ($v as $_k=>$_v) {
					   if (strtolower($_k) == 'in') {
					       $_v = (array)$_v;
					       $_v = array_map(function($elem) {
					           return $this->_parseValue($elem);
					       }, $_v);					       
					       $sqlWhere .= sprintf(" %s ",strtoupper($_k)).'('.implode(',', $_v).') '; 
					   } else {
					       $sqlWhere .= sprintf(" %s ",strtoupper($_k)).$this->_parseValue($_v);
					   }
					}
				}else{
					$sqlWhere .= '='.$this->_parseValue($v);
				}
			}
		}else{
			$sqlWhere = $where;
		}
		return $sqlWhere;
	}


	/**
	 * 解析 UPDATE 操作字段设置
	 *
	 * @param array $arrSet
	 * @return string
	 */
	private function _parseUpdateSet($arrSet)
	{
		$sqlSet = $spr = '';
		if (is_array($arrSet))
		{
			foreach ($arrSet as $k=>$v)
			{
				$sqlSet .= $spr.$this->_parseField($k).'='.$this->_parseValue($v);
				$spr = ',';
			}
		}else{
			$sqlSet = $arrSet;
		}
		return $sqlSet;
	}

	/**
	 * 解析 INSERT 操作字段设置
	 *
	 * @param array $arrSet
	 * @return array
	 */
	private function _parseInsertSet($arrSet)
	{
		$Keys = $Values = $spr = '';
		foreach ($arrSet as $k=>$v)
		{
			$Keys .= $spr.$this->_parseField($k);
			$Values .= $spr.$this->_parseValue($v);
			$spr = ',';
		}
		return array('key'=>$Keys,'val'=>$Values);
	}
}
?>
<?php
class mysqlDB extends PDO {
	private $_link = '';
	public function __construct($file = 'my_setting.ini') {
		if (! $settings = parse_ini_file ( $file, TRUE ))
			throw new exception ( 'Unable to open ' . $file . '.' );
		
		$dns = $settings ['database'] ['driver'] . ':host=' . $settings ['database'] ['host'] . ((! empty ( $settings ['database'] ['port'] )) ? (';port=' . $settings ['database'] ['port']) : '') . ';dbname=' . $settings ['database'] ['schema'] . ';charset=UTF8';
		
		$this->_link = parent::__construct ( $dns, $settings ['database'] ['username'], $settings ['database'] ['password'] );
	}
	
	/**
	 * insert
	 * @param string $table
	 * @param array $info
	 */
	public function insert($table, $info) {
		$ret = $this->_parseInsertSet($info);
		
		$sql = sprintf("INSERT INTO %s(%s) VALUES(%s)",$table,$ret['key'],$ret['val']);
		$result = $this->run($sql);
		if($result){
			$result = $this->lastInsertId();
		}
		
		return $result;
	}

	/**
	 * delete
	 * @param string $table
	 * @param array $options
	 */
	public function delete($table, $options=array()) {
		$sql = "DELETE FROM $table";
		
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		if (isset($options['limit'])) $sql .= ' LIMIT '.$options['limit'];
		
		$result = $this->run($sql);		
		return $result;
	}
	
	/**
	 * update
	 * @param string $table
	 * @param array $arrSets
	 * @param array $options
	 */
	public function update($table, $arrSets=array(), $options=array()) {
		$sqlSet = $this->_parseUpdateSet($arrSets);
		$sql = sprintf("UPDATE %s SET %s", $table, $sqlSet);
		
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		if (isset($options['limit'])) $sql .= ' LIMIT '.$options['limit'];
		
		$result = $this->run($sql);		
		return $result;
	}
	
	/**
	 * select
	 * @param string $table
	 * @param string $fields
	 * @param array $options
	 */
	public function select($table, $fields = '*', $options=array()) {
		$sql = 'SELECT '.$fields.' FROM '.$table;
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['having'])) $sql .= ' HAVING '.$options['having'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		if (isset($options['limit'])) $sql .= ' LIMIT '.$options['limit'];
		return $this->run($sql);
	}
	
	/**
	 * selectOne
	 * @param string $table
	 * @param string $fields
	 * @param array $options
	 */
	public function selectOne($table, $fields, $options=array()) {
		$sql = 'SELECT '.$fields.' FROM '.$table;
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		if (isset($options['having'])) $sql .= ' HAVING '.$options['having'];
		if (isset($options['order'])) $sql .= ' ORDER BY '.$options['order'];
		$sql .= ' LIMIT 1';
		$ret = $this->fetchRow($sql);
		return $ret;
	}
	
	public function count($table, $countField, $options=array()) {
		$sql = sprintf("SELECT COUNT(%s) FROM %s", $countField, $table);
		
		if (isset($options['where'])) $sql .= ' WHERE '.$this->_parseWhere($options['where']);
		if (isset($options['group'])) $sql .= ' GROUP BY '.$options['group'];
		
		$row = $this->fetchRow($sql);
		if ($row) return current($row);
		return 0;
	}
	
	/**
	 * @param string $sql
	 * @param string|array $bind
	 */
	public function run($sql, $bind = '') {
		$this->sql = trim ( $sql );
		$this->bind = $this->cleanup($bind);
		$this->error = '';
		
		try {
			$pdostmt = $this->prepare ( $this->sql );
			if ($pdostmt->execute ( $this->bind ) !== false) {
				if (preg_match ( '/^(' . implode ( '|', array ('select', 'describe', 'pragma' ) ) . ') /i', $this->sql ))
					return $pdostmt->fetchAll ( PDO::FETCH_ASSOC );
				elseif (preg_match ( '/^(' . implode ( '|', array ('delete', 'insert', 'update') ) . ') /i', $this->sql ))
					return $pdostmt->rowCount ();
			}
		} catch ( PDOException $e ) {
			$this->error = $e->getMessage ();
			return false;
		}
	}
	
	/**
	 * string to array
	 * @param string|array $bind
	 */
	private function cleanup($bind) {
		if(!is_array($bind)) {
			if(!empty($bind))
				$bind = array($bind);
			else
				$bind = array();
		}
		return $bind;
	}
	
	/**
	 * 解析 INSERT 操作字段设置
	 *
	 * @param array $arrSet
	 * @return array
	 */
	private function _parseInsertSet($info){
		$Keys = $Values = $spr = '';
		foreach ($info as $k=>$v)
		{
			$Keys .= $spr.$this->_parseField($k);
			$Values .= $spr.$this->_parseValue($v);
			$spr = ',';
		}
		return array('key'=>$Keys,'val'=>$Values);
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
					foreach ($v as $_k=>$_v) $sqlWhere .= sprintf(" %s ",strtoupper($_k)).$this->_parseValue($_v);
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
	 * 解析字段名,防止字段名是关键字
	 */
	private function _parseField($fieldName){
		return '`'.$fieldName.'`';
	}
	
	/**
	 * 根据值的类型返回SQL语句式的值
	 *
	 * @param unknown_type $val
	 * @return unknown
	 */
	private function _parseValue($val){
		if (is_int($val) || is_float($val)) return $val;
		else return $this->escapeValue($val);
	}
	
	/**
	 * 格式化用于数据库的安全字符串
	 *
	 * @param string $value
	 * @return string
	 */
	private function escapeValue($value){
		$value = $this->quote($value);
		return $value;
	}
	
	private function fetchRow($sql, $bind=''){
		$ret = $this->run($sql, $bind);
		return empty($ret) ? array() : $ret[0];
	}
}

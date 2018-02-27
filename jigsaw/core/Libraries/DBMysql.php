<?php
namespace Jigsaw\Libraries;

use PDO;

class DBMysql
{

    private $_link = '';

    private $forceMaster = false;

    private $DBConfigs = [];

    private $DBConnectPools = [];

    public function __construct($DBConfigs)
    {
        $this->setConfigs($DBConfigs);
    }

    public function setConfigs($DBConfigs)
    {
        $this->DBConfigs = $DBConfigs;
    }

    /**
     * insert
     *
     * @param string $table            
     * @param array $info            
     */
    public function insert($table, $info)
    {
        $ret = $this->_parseInsertSet($info);
        
        $sql = sprintf("INSERT INTO %s(%s) VALUES(%s)", $table, $ret['key'], $ret['val']);
        $result = $this->exec($sql);
        if ($result) {
            $result = $this->_link->lastInsertId();
        }
        
        return $result;
    }

    /**
     * delete
     *
     * @param string $table            
     * @param array $options            
     */
    public function delete($table, $options = [])
    {
        $sql = sprintf("DELETE FROM %s", $table);
        
        if (isset($options['where'])) {
            $sql .= ' WHERE ' . $this->_parseWhere($options['where']);
        }
        if (isset($options['group'])) {
            $sql .= ' GROUP BY ' . $options['group'];
        }
        if (isset($options['order'])) {
            $sql .= ' ORDER BY ' . $options['order'];
        }
        if (isset($options['limit'])) {
            $sql .= ' LIMIT ' . $options['limit'];
        }
        
        $result = $this->exec($sql);
        return $result;
    }

    /**
     * update
     *
     * @param string $table            
     * @param array $arrSets            
     * @param array $options            
     */
    public function update($table, $arrSets = [], $options = [])
    {
        $sqlSet = $this->_parseUpdateSet($arrSets);
        $sql = sprintf("UPDATE %s SET %s", $table, $sqlSet);
        
        if (isset($options['where'])) {
            $sql .= ' WHERE ' . $this->_parseWhere($options['where']);
        }
        if (isset($options['group'])) {
            $sql .= ' GROUP BY ' . $options['group'];
        }
        if (isset($options['order'])) {
            $sql .= ' ORDER BY ' . $options['order'];
        }
        if (isset($options['limit'])) {
            $sql .= ' LIMIT ' . $options['limit'];
        }
        
        $result = $this->exec($sql);
        return $result;
    }

    /**
     * select
     *
     * @param string $table            
     * @param string $fields            
     * @param array $options            
     */
    public function select($table, $fields = '*', $options = [])
    {
        $sql = sprintf("SELECT %s FROM %s", $fields, $table);
        
        if (isset($options['where'])) {
            $sql .= ' WHERE ' . $this->_parseWhere($options['where']);
        }
        if (isset($options['group'])) {
            $sql .= ' GROUP BY ' . $options['group'];
        }
        if (isset($options['having'])) {
            $sql .= ' HAVING ' . $options['having'];
        }
        if (isset($options['order'])) {
            $sql .= ' ORDER BY ' . $options['order'];
        }
        if (isset($options['limit'])) {
            $sql .= ' LIMIT ' . $options['limit'];
        }
        return $this->fetch($sql);
    }

    /**
     * selectOne
     *
     * @param string $table            
     * @param string $fields            
     * @param array $options            
     */
    public function selectOne($table, $fields, $options = [])
    {
        $sql = sprintf("SELECT %s FROM %s", $fields, $table);
        if (isset($options['where'])) {
            $sql .= ' WHERE ' . $this->_parseWhere($options['where']);
        }
        if (isset($options['group'])) {
            $sql .= ' GROUP BY ' . $options['group'];
        }
        if (isset($options['having'])) {
            $sql .= ' HAVING ' . $options['having'];
        }
        if (isset($options['order'])) {
            $sql .= ' ORDER BY ' . $options['order'];
        }
        $sql .= ' LIMIT 1';
        $ret = $this->fetchRow($sql);
        return $ret;
    }

    public function count($table, $countField, $options = [])
    {
        $sql = sprintf("SELECT COUNT(%s) FROM %s", $countField, $table);
        
        if (isset($options['where'])) {
            $sql .= ' WHERE ' . $this->_parseWhere($options['where']);
        }
        if (isset($options['group'])) {
            $sql .= ' GROUP BY ' . $options['group'];
        }
        
        $row = $this->fetchRow($sql);
        if ($row) {
            return current($row);
        }
        
        return 0;
    }

    /**
     * 用来执行update、delete等改变数据或数据库结构的行为
     *
     * @param unknown $sql            
     * @param string $bind            
     * @throws Exception
     * @return boolean
     */
    public function exec($sql, $bind = '')
    {
        $this->sql = trim($sql);
        $this->bind = $this->cleanup($bind);
        $this->error = '';
        
        try {
            $this->setCurrentConnect(true);
            
            $this->setForceMaster();
            
            $pdostmt = $this->_link->prepare($this->sql);
            if ($pdostmt->execute($this->bind) !== false) {
                return $pdostmt->rowCount();
            } else {
                throw new Exception('执行失败');
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 用来执行select、show等不改变数据和数据库结构的行为
     *
     * @param unknown $sql            
     * @param string $bind            
     * @throws Exception
     * @return boolean
     */
    public function fetch($sql, $bind = '')
    {
        $this->sql = trim($sql);
        $this->bind = $this->cleanup($bind);
        $this->error = '';
        
        try {
            $this->setCurrentConnect(false);
            
            $pdostmt = $this->_link->prepare($this->sql);
            if ($pdostmt->execute($this->bind) !== false) {
                return $pdostmt->fetchAll();
            } else {
                throw new Exception('执行失败');
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getLastInsertId()
    {
        return $this->_link->lastInsertId();
    }

    /**
     * string to array
     *
     * @param string|array $bind            
     */
    private function cleanup($bind)
    {
        if (! is_array($bind)) {
            if (! empty($bind)) {
                $bind = [
                    $bind
                ];
            } else {
                $bind = [];
            }
        }
        return $bind;
    }

    /**
     * 解析 INSERT 操作字段设置
     *
     * @param array $arrSet            
     * @return array
     */
    private function _parseInsertSet($info)
    {
        $Keys = $Values = $spr = '';
        foreach ($info as $k => $v) {
            $Keys .= $spr . $this->_parseField($k);
            $Values .= $spr . $this->_parseValue($v);
            $spr = ',';
        }
        return [
            'key' => $Keys,
            'val' => $Values
        ];
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
        if (is_array($arrSet)) {
            foreach ($arrSet as $k => $v) {
                $sqlSet .= $spr . $this->_parseField($k) . '=' . $this->_parseValue($v);
                $spr = ',';
            }
        } else {
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
            foreach ($where as $fieldname=>$fieldvalue)
            {
                $sqlWhereValue = '';
                $andOr = ' AND ';
                if (is_array($fieldvalue))
                {
                    foreach ($fieldvalue as $k=>$v) {
                        if (strtolower($k) == 'or') {
                            $andOr = ' OR ';
                            if (is_array($v)) {
                                $ret = each($v);
                                $sqlWhereValue .= $this->_parseFieldValue($ret['key'], $ret['value']);
                            } else {
                                $sqlWhereValue .= '='.$this->_parseValue($v);
                            }
                        } else {
                            $sqlWhereValue .= $this->_parseFieldValue($k, $v);
                        }
                    }
                }else{
                    $sqlWhereValue .= '='.$this->_parseValue($fieldvalue);
                }
                $sqlWhere .= ($andOr . $this->_parseField($fieldname) . $sqlWhereValue);
            }
        }else{
            $sqlWhere = $where;
        }
        return $sqlWhere;
    }
    
    /**
     * 解析where字段的值，解决in的情况
     * @param unknown $k
     * @param unknown $v
     * @return string
     */
    private function _parseFieldValue($k, $v)
    {
        $sqlWhereValue = '';
        if (strtolower($k) == 'in') {
            $v = (array)$v;
            $v = array_map(function($elem) {
                return $this->_parseValue($elem);
            }, $v);
            $sqlWhereValue .= sprintf(" %s ",strtoupper($k)).'('.implode(',', $v).') ';
        } else {
            $sqlWhereValue .= sprintf(" %s ",strtoupper($k)).$this->_parseValue($v);
        }
        return $sqlWhereValue;
    }

    /**
     * 解析字段名,防止字段名是关键字
     */
    private function _parseField($fieldName)
    {
        return '`' . $fieldName . '`';
    }

    /**
     * 根据值的类型返回SQL语句式的值
     *
     * @param unknown_type $val            
     * @return unknown
     */
    private function _parseValue($val)
    {
        if (is_int($val) || is_float($val))
            return $val;
        else
            return $this->escapeValue($val);
    }

    /**
     * 格式化用于数据库的安全字符串
     *
     * @param string $value            
     * @return string
     */
    private function escapeValue($value)
    {
        if (empty($this->_link)) {
            $this->setCurrentConnect(false);
        }
        $value = $this->_link->quote($value);
        return $value;
    }

    private function fetchRow($sql, $bind = '')
    {
        $ret = $this->fetch($sql, $bind);
        return empty($ret) ? [] : $ret[0];
    }

    private function getDBConnect($configs)
    {
        $dns = 'mysql:host=' . $configs['host'] . ';port=' . $configs['port'] . ';dbname=' . $configs['database'] . ';charset=UTF8';
        
        $pdoParam = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
            PDO::ATTR_PERSISTENT => $configs['pconnect'] ? true : false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC 
        ];
        
        $dbLink = new PDO($dns, $configs['user'], $configs['password'], $pdoParam);
        return $dbLink;
    }

    /**
     * 设置当前的DB handle
     *
     * @param string $isMaster
     *            是否使用主库
     */
    private function setCurrentConnect($isMaster = false)
    {        
        if ($this->getForceMaster()) {
            $isMaster = true;
        }
        
        if ($isMaster) {
            $configs = $this->DBConfigs['master'];
            $key = 'master';
        } else {
            $randval = array_rand($this->DBConfigs['slave']);
            $configs = $this->DBConfigs['slave'][$randval];
            $key = 'slave';
        }
        
        if (empty($this->DBConnectPools[$key])) {
            $this->DBConnectPools[$key] = $this->getDBConnect($configs);
        }
        
        return $this->_link = $this->DBConnectPools[$key];
    }

    private function setForceMaster()
    {
        $this->forceMaster = true;
    }

    private function getForceMaster()
    {
        return $this->forceMaster;
    }
}

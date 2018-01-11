<?php
	class CYdmatchteamdatasum extends IARO
	{
		
		/**
		 * 初始化函数
		 *
		 * 抽象接口实现，设置表及主键
		 */
		public function init()
		{
			$this->_table = 'ydmatch_teamdatasum';
			$this->_prkey = 'id';
			$this->_isActivity = true;
		}
		
		public function deleteByChangci($changci)
		{		    
		    $sql = "delete from `{$this->_table}` where `changci`={$changci} ";
		    $this->doQueryInfo($sql);
		}
	}

?>
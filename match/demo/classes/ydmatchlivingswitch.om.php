<?php
	class CYdmatchlivingswitch extends IARO
	{
		
		/**
		 * 初始化函数
		 *
		 * 抽象接口实现，设置表及主键
		 */
		public function init()
		{
			$this->_table = 'ydmatch_livingswitch';
			$this->_prkey = 'id';
			$this->_isActivity = true;
		}
	}

?>
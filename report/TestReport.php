<?php
require 'reportBase.class.php';
class testReport extends reportBase {

	protected $from = 'ljqian@163.com';
	
	protected $to = '318474425@qq.com';
	
	protected $title = '测试';
	
	protected $template = 'TestReport.tpl';
	
	protected function getList(){
		return array('name'=>'钱立嘉', 'age'=>27);
	}
}

$tr = new testReport();
echo $tr->send();
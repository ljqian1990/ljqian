<?php
require 'mail.php';
class reportBase {	
	/**
	 * 邮件内容实体变量
	 */
	private $body;
	
	/**
	 * 规定发送者、接收者、邮件抬头、邮件模版的值
	 */
	protected 
	$from,
	$to,	
	$title,	
	$template;
	
	public function __construct() {
		if (empty($this->from) || empty($this->to) || empty(this->title) || empty($this->template)){
			return 'param can not be empty!';
		}
		$this->body = $this->renderFile($this->getList(), $this->template);
		if ($this->body === false){
			return 'template not real exist!';
		}
	}
	
	/**
	 * 发送邮件
	 * @return string
	 */
	public function send(){
		try {
			$mail = new ReportMailer($this->from, $this->to, $this->title, $this->body);
			return 'ok';
		}catch (Exception $e){
			return 'false';
		}
	}
	
	/**
	 * 数据列表获取
	 * @return multitype:
	 */
	protected function getList() {
		return array();
	}
	
	/**
	 * 模版生成器
	 * @param array $vars
	 * @param string $template
	 * @return boolean|string
	 */
	protected function renderFile($vars, $template) {		
		$result = '';
		if (file_exists ( $template )) {
			
			foreach ( $vars as $key => $value ) {
				$$key = $value;
			}
			
			ob_start ();
			require ($template);
			$result = ob_get_contents ();
			ob_end_clean ();						
		}else{
			return false;
		}
		
		return $result;
	}
}
<?php
class divide implements operateType {
	
	public function operate($num1, $num2){
		try {
			return $num1 / $num2;
		}catch (Exception $ex){
			return $ex->getMessage();
		}		 
	}
}
<?php
class Customer {	
	
	public function getPrice($order){
		return $order->getDiscountedPrice($this);
	}
	
	public function getDiscount(){
		return 0.75;
	}
}
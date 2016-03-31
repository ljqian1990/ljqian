<?php
class Order {		
	public function getDiscountedPrice($customer){
		return 10000 * $customer->getDiscount();
	}
}
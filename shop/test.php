<?php
require_once 'customer.class.php';
require_once 'order.class.php';

$customer = new Customer();
$order = new Order();

echo $customer->getPrice($order);
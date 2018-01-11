<?php
use Swoole\Table;
$table = new \Swoole\Table(8);
$table->column('data',Table::TYPE_STRING, 1);
$table->create();
$table->set(1,[]);
var_dump($table->get(1));
//result: Bus error


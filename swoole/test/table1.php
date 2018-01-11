<?php
use Swoole\Table;
$table = new \Swoole\Table(8);

$table->column('data',Table::TYPE_STRING, 1);
$table->column('pad',Table::TYPE_STRING, 1);
$table->column('incr', Table::TYPE_INT, 2);
$table->create();

$table->set('ljqian',['data'=>'a', 'pad'=>'b', 'incr'=>1]);
var_dump($table->get('ljqian'));
$table->incr('ljqian', 'incr');
$table->incr('ljqian', 'incr');
$table->incr('ljqian', 'incr');
$table->incr('ljqian', 'incr');
var_dump($table->get('ljqian'));

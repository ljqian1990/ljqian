<?php
$queue_id = 10086; // 定义一个队列id
//if (! msg_queue_exists($queue_id)) { // 检测队列id是否存在，即被使用
    $queue = msg_get_queue($queue_id);
//}
// 展示消息队列的统计信息
$queue_info = msg_stat_queue($queue);
print_r($queue_info);exit;
// 从消息队列中接收消息，消息是以引用的方式获取
msg_receive($queue, 0, $msg_typei, 1024, $msg, true, null, $error);
print_r(unserialize($msg));
//msg_remove_queue($queue); //销毁消息队列
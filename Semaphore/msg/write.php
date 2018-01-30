<?php
$text = $_GET['text'];
$queue_id = 1; // 定义一个队列id
//if (! msg_queue_exists($queue_id)) { // 检测队列id是否存在，即被使用
    $queue = msg_get_queue($queue_id);
//}

if (! $queue) {
    echo 'false';exit;
}
    
// 向消息队列发送消息
msg_send($queue, 1, serialize(array(
    'a' => 'hello world',
    'name' => '测试',
    'text' => $text
)));
// 展示消息队列的统计信息
$queue_info = msg_stat_queue($queue);

// 从消息队列中接收消息，消息是以引用的方式获取
msg_receive($queue, 0, $msg_typei, 1024, $msg, true, null, $error);
print_r(unserialize($msg));
//msg_remove_queue($queue); //销毁消息队列
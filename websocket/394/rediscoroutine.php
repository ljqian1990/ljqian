<?php
$redis = new Swoole\Coroutine\Redis();
$redis->connect('w2.dev.ztgame.com', 6379);
while (true) {
    $val = $redis->subscribe(['pubsub']);
    //���ĵ�channel���Ե�һ�ε���subscribeʱ��channelΪ׼��������subscribe������Ϊ����ȡRedis Server�Ļذ�
    //�����Ҫ�ı䶩�ĵ�channel����close�����ӣ��ٵ���subscribe
    var_dump($val);
}

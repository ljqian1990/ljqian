<?php
$str = "<iframe src='http://player.youku.com/embed/XMTcxMjc4MjkwMA==' frameborder=0></iframe>";

$starttime = microtime(true);
$list = array();
foreach (range(0, 10000) as $num) {
    if (preg_match('/src=[\'|"](.*?)[\'|"]/i', $str, $match)) {
        $list[] = $match[1];
    }    
}

$endtime = microtime(true);
echo $endtime-$starttime;

echo '<br>';



$starttime = microtime(true);
$list = array();
foreach (range(0, 10000) as $num) {
    $doc = new DOMDocument();
    $doc->loadHTML($str);
    $ret = $doc->getElementsByTagName('iframe');
    foreach ($ret as $iframe) {
        $list[] = $iframe->getAttribute('src') . PHP_EOL;
    }    
}
$endtime = microtime(true);
echo $endtime-$starttime;


// $doc = new DOMDocument();
// $doc->loadHTML("<html><body>Test<br></body></html>");
// echo $doc->saveHTML();
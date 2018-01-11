<?php
include 'Gamecrypt.php';
// $str = '+qrXRlYQeTxBHZDryHKxPIniCWyx9PEc644dyDLv0dMOlOFE28zTXrg0/DtbA9+h4t4P4WSoDG0AN7lBK4ltZeF36vsIusgcFVRrTY3Gx5ow2nDKqTFsvBWvy0KGVqMFplsEei7g7NWrf3AU6MZ3btwJHuZMbdWrc2WYYiM9cURq6BDfvH/VvELoxidrubFTYw/Ksk5DS9fvpyII/iZeJ1ODwYSqyr4CmJVJP67TRxTOgRdrfs7VpEyZ2T3AkxQfyNriq5+vWQLhwQZ6vOygum5NjlF8cfgxYbpaMUEJKXwaLgJs27jvdi5qy8oMyypPrZeG9d8m6H2NpHPKfkUSbg==';
// $str = 'gjBjiEh9D6WIRwGFbXLjMQ==';
// $str = 'jROJh27M6beOT0T7ryxeuAJRvtidZ5CQQSxf5bnaRjCZpm8/eoZsz2wirPFI4KWDZbrPxOy8rKh/u6y4SGNXYN35214bZ6o2kyX++hh46bdMUzYfRrlH1gkP3dQkX7KadMFdOG1yNzQM69xhwuHq+ED/bIDtIUoWIgKamUi7Vq9HU6/fCpQX/YehWE49xpyNI/u5ui4yXcir/o9vIJhrp30ZP/C+4yPUrsS+QhRP33d8g8oiiwEDBIkbhOm2Ma6WKcEYJaOn0A+BwpTFjijVQlLF4TFBYwHz9KCftOvdS0f8iiwaYSxW1VLESKATJBD4DutNymNTDL0Do8vk7N3uwg==';
// $str = '+KCJMtMMRIJcWhN3yqwE+XEablmjDzbr2uy+TqvXCVes/A66mhdrYLyLZI1cZASwOe+XIrSHiH3kcExUBCILWmAXzIcZ5bNjfu7N9GgfvMyvQWTMjYRsam/wWCpfIFcX7gv3lsRsVK5HI5wYaQp+b6GaRStFixnyY2Zcd3V/C46mykX/KfzjhJvNmTnKz5Nuvc/YbyEo6+ppXEcP3E0KT8GCcXdH/JyN1bSllbZZL7VqD0714NXW9rOHksvfbLGEYDQKDcaHXyhutNCKauiai5CgjqpQ8e74EzO0JoSM7Mc=';
$str = '+jmNKqlMbyk4PXmic+SKudpJaumxDs5e4kwFDbGqAZerQvtUMKmVEtX4t0Uve//bvh2UcoJyO3hyY5WnyBq6DwD7229UmCC0gcUI/SPuA+9CWG5ETYRlytcJ92SyJAvqfT4rsX4fhpg3KyjVTc8T55UppCxxWibyPKx/H+AktsPqEZPjidtNXX9SG+zBXBoSr1vcXLa2lbGt4Psp7goFz+60ez5WKHAOeNzOOwjTooNcAqKujl93k04Md6ZcRirF+Wdf92jfp8Qt9lPoJuevw6E6LdLGXlEv78OH8RTj74ZlbmuldJhUao3hLR1GaynQ5aoJeIHQF1zb7S/IYrkr9w==';
// $str = base64_decode($str);
// var_dump(base64_decode($str));exit;
$g = new Gamecrypt();
$str = $g->decrypt($str);
$str = base64_decode($str);
$arr = explode('&', $str);
$param = [];
foreach ($arr as $v) {
    $tmp = explode('=',  $v);
    $param[$tmp[0]] = $tmp[1];    
}

$sign = substr(md5($param['id'] . $param['Account'] . $param['p'] . '*(*&&%$$%^&'), 0, 6);

echo 'account:'.$param['Account'].'<br>';
echo 'qqid:'.$param['QQID'].'<br>';
echo 'p:'.$param['p'].'<br>';
echo 's:'.$param['s'].'<br>';
echo 'trues:'.$sign.'<br>';
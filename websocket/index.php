<?php

echo time();exit;
function _setCookie($key, $value, $time=86400, $isPriDomain=false)
{
    $_COOKIE[$key] = $value;        
    if ($isPriDomain) {
        setcookie($key, $value, time()+$time, '/', Config::DOMAIN_PRI_URL);
    } else {
        setcookie($key, $value, time()+$time, '/');
    }
    
}

_setCookie('ljqian', 'test');
header('Location:/websocket/index.html');
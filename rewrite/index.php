<?php
phpinfo();exit;
$result = apache_get_modules();
if (in_array('mod_rewrite', $result)) {
    echo '1';
} else {
    echo '0';
}
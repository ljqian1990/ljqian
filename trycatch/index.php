<?php
$arr = array(
    1,
    2,
    3
);

class test
{
    
}

function test($ar, $ar2, $ar3)
{
    throw new Exception('first');
    throw new Exception('second');
    
    throw new Exception('thrid');
}

try {
    test();
} catch (Exception $ex) {
    echo $ex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
} finally {
    echo 'finally';
}
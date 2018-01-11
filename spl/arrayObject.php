<?php
$arr = array('name'=>'ljqian', 'sex'=>1, 'age'=>27);
$arrobj = new ArrayObject($arr);
$it = $arrobj->getIterator();

$it -> rewind();
while($it -> valid()){
  echo $it -> key().':'.$it -> current().'<br />';
  $it -> next();
}

echo "<br>";
$it2 = new ArrayIterator($arr);
$it2 -> rewind();
while($it2 -> valid()){
  echo $it2 -> key().':'.$it2 -> current().'<br />';
  $it2 -> next();
}
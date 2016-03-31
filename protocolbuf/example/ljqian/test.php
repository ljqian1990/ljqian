<?php


$starttime = time();
// first include pb_message
require_once('../../message/pb_message.php');

// include the generated file
require_once('./pb_proto_test.php');
$human = new Human();
$arr = array();
for ($i=1; $i<=100000; $i++){
	$person = new Person();	
	$person->set_name('ljqian'.$i);
	$person->set_id($i);
	$human->append_person($person->SerializeToString());
	array_push($arr, array('name'=>'ljqian'.$i, 'id'=>$i));
}

$str = $human->SerializeToString();
file_put_contents('./pb_new2.txt', $str);
// echo 'pb '.(time()-$starttime);

file_put_contents('./json.txt', json_encode(array('human'=>$arr)));

// echo 'json .()time()-$starttime';


require_once('../../message/pb_message.php');
require_once('./pb_proto_test.php');
$human = new Human();
$person = $human->add_person();
$person->set_name('ljqian');
$person->set_id(1);
$string = $human->SerializeToString();
file_put_contents('./pb.txt', $string);


?>
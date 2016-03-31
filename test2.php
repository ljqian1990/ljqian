<?php
$m = 14000;
for ($i=1; $i<=40; $i++){
	$m = $m*1.15;
	echo round($m, 2)."<br/>";
}

echo $m;
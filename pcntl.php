<?php

set_time_limit ( 0 );
$fp = fopen ( 'jr-card.txt', 'r' );
while ( ! feof ( $fp ) ) {
	$line = fgets ( $fp );
	list ( $cardid, $cardtype, $createtime, $status ) = explode ( ',', $line );
	file_put_contents ( 'jr-card-tmp.txt', $cardid . "\n", FILE_APPEND );
	unset ( $line, $cardid, $cardtype, $createtime, $status );
}
echo 'OK';
 
// echo "load data infile 'd:/data.txt' into table card LINES TERMINATED BY '\n' (`code`)";
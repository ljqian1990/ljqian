<?php
$expr = 0;
switch ($expr) {
	case 0:
		echo 'First case, with a break';
		
	case 1:
		echo 'Second case, which falls through';
		// no break
	case 2:
	case 3:
	case 4:
		echo 'Third case, return instead of break';
		return;
	default:
		echo 'Default case';
		break;
}